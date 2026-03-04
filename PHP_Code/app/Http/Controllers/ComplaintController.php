<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Services\ResponseService;
use App\Services\BootstrapTableService;
use App\Repositories\Complaint\ComplaintInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    protected $complaint;

    public function __construct(ComplaintInterface $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Admin: Show complaints list page.
     */
    public function index()
    {
        // Only Super Admin (no school_id) can view complaints
        if (!Auth::user() || Auth::user()->school_id) {
            return redirect('/')->with('error', 'Unauthorized');
        }

        return view('complaints.index');
    }

    /**
     * Admin: Server-side paginated JSON for BootstrapTable.
     */
    public function show(Request $request)
    {
        if (!Auth::user() || Auth::user()->school_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'DESC');

        $showDeleted = in_array(request('show_deleted', 0), [true, 1, '1', 'true'], true);

        $sql = Complaint::query();

        if ($showDeleted) {
            $sql = $sql->onlyTrashed();
        }

        // Filter by status
        if (!empty($request->status)) {
            $sql->where('status', $request->status);
        }

        // Filter by category
        if (!empty($request->category)) {
            $sql->where('category', $request->category);
        }

        // Search across multiple fields
        if (!empty($request->search)) {
            $search = $request->search;
            $sql->where(function ($query) use ($search) {
                $query->where('user_name', 'LIKE', "%{$search}%")
                    ->orWhere('contact_info', 'LIKE', "%{$search}%")
                    ->orWhere('message', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        $total = $sql->count();
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit;
            $offset = $lastPage;
        }

        $res = $sql->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $bulkData['total'] = $total;
        $rows = [];
        $no = $offset + 1;

        foreach ($res as $row) {
            $operate = '';
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;

            // Status badge
            $rawStatus = $row->getRawOriginal('status') ?? 'new';
            $badgeClass = match ($rawStatus) {
                    'new' => 'badge-danger',
                    'in_progress' => 'badge-warning',
                    'resolved' => 'badge-success',
                    default => 'badge-secondary',
                };
            $statusLabel = match ($rawStatus) {
                    'new' => __('New'),
                    'in_progress' => __('In Progress'),
                    'resolved' => __('Resolved'),
                    default => $rawStatus,
                };
            $tempRow['status_display'] = '<span class="badge ' . $badgeClass . '">' . $statusLabel . '</span>';

            // Category badge
            $categoryLabel = ucfirst($row->category ?? 'general');
            $tempRow['category_display'] = '<span class="badge badge-info">' . e($categoryLabel) . '</span>';

            // Contact type badge
            $contactTypeLabel = ucfirst($row->contact_type ?? 'email');
            $tempRow['contact_type_display'] = '<span class="badge badge-secondary">' . e($contactTypeLabel) . '</span>';

            if ($showDeleted) {
                $operate = BootstrapTableService::restoreButton(route('complaints.restore', $row->id));
                $operate .= BootstrapTableService::trashButton(route('complaints.destroy', $row->id));
            }
            else {
                // Status change buttons
                if ($rawStatus !== 'resolved') {
                    $nextStatus = $rawStatus === 'new' ? 'in_progress' : 'resolved';
                    $nextLabel = $nextStatus === 'in_progress' ? __('Mark In Progress') : __('Mark Resolved');
                    $btnClass = $nextStatus === 'in_progress' ? 'btn-gradient-warning' : 'btn-gradient-success';
                    $operate .= '<a href="' . route('complaints.update-status', $row->id) . '" class="btn btn-xs btn-rounded btn-icon change-complaint-status ' . $btnClass . '" data-status="' . $nextStatus . '" title="' . $nextLabel . '"><i class="fa fa-check"></i></a>&nbsp;&nbsp;';
                }
                $operate .= BootstrapTableService::deleteButton(route('complaints.trash', $row->id));
            }

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    /**
     * Public chatbot API: Store a new complaint.
     * No authentication required — rate-limited instead.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:10|max:2000',
            'category' => 'nullable|string|max:100',
            'user_name' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'bot_message' => __('Silakan berikan pesan keluhan yang valid (minimal 10 karakter).'),
                'errors' => $validator->errors(),
            ], 422);
        }

        // Extract contact info from explicit field or from the message body
        $contactInfo = $request->input('contact_info', '');
        $contactType = 'email';
        $message = $request->input('message', '');

        // If explicit contact_info provided, validate it
        if (!empty($contactInfo)) {
            $detected = $this->detectContactType($contactInfo);
            if (!$detected) {
                return response()->json([
                    'success' => false,
                    'bot_message' => __('Informasi kontak yang Anda berikan sepertinya bukan email, nomor telepon, atau username yang valid. Silakan coba lagi.'),
                    'needs_contact' => true,
                ], 422);
            }
            $contactType = $detected;
        }
        else {
            // Try to extract from the message
            $extracted = $this->extractContactFromMessage($message);
            if (!$extracted) {
                return response()->json([
                    'success' => false,
                    'bot_message' => __('Kami tidak dapat menemukan informasi kontak di pesan Anda. Silakan berikan alamat email, nomor telepon, atau username Anda agar admin dapat menghubungi Anda.'),
                    'needs_contact' => true,
                ], 422);
            }
            $contactInfo = $extracted['value'];
            $contactType = $extracted['type'];
        }

        // Store the complaint
        try {
            $complaint = Complaint::create([
                'user_name' => $request->input('user_name', null),
                'contact_info' => $contactInfo,
                'contact_type' => $contactType,
                'message' => $message,
                'category' => $request->input('category', 'general'),
                'status' => 'new',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'bot_message' => __('Terima kasih! Keluhan Anda telah berhasil dikirim. Tim kami akan meninjaunya dan segera menghubungi Anda melalui ') . $contactType . ' yang Anda berikan.',
                'complaint_id' => $complaint->id,
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'bot_message' => __('Maaf, terjadi kesalahan saat mengirim keluhan Anda. Silakan coba lagi nanti.'),
            ], 500);
        }
    }

    /**
     * Admin: Update complaint status.
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::user() || Auth::user()->school_id) {
            return ResponseService::errorResponse('Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,in_progress,resolved',
        ]);

        if ($validator->fails()) {
            return ResponseService::errorResponse('Invalid status value');
        }

        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->update(['status' => $request->status]);

            ResponseService::successResponse('Complaint status updated successfully');
        }
        catch (\Exception $e) {
            ResponseService::errorResponse('Error updating complaint status: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Soft delete a complaint.
     */
    public function trash($id)
    {
        if (!Auth::user() || Auth::user()->school_id) {
            return ResponseService::errorResponse('Unauthorized');
        }

        try {
            $this->complaint->builder()->where('id', $id)->delete();
            ResponseService::successResponse('Complaint moved to trash successfully');
        }
        catch (\Exception $e) {
            ResponseService::errorResponse('Error deleting complaint: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Restore a soft-deleted complaint.
     */
    public function restore($id)
    {
        if (!Auth::user() || Auth::user()->school_id) {
            return ResponseService::errorResponse('Unauthorized');
        }

        try {
            $this->complaint->builder()->onlyTrashed()->where('id', $id)->restore();
            ResponseService::successResponse('Complaint restored successfully');
        }
        catch (\Exception $e) {
            ResponseService::errorResponse('Error restoring complaint: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Permanently delete a complaint.
     */
    public function destroy($id)
    {
        if (!Auth::user() || Auth::user()->school_id) {
            return ResponseService::errorResponse('Unauthorized');
        }

        try {
            $this->complaint->builder()->onlyTrashed()->where('id', $id)->forceDelete();
            ResponseService::successResponse('Complaint permanently deleted');
        }
        catch (\Exception $e) {
            ResponseService::errorResponse('Error permanently deleting complaint: ' . $e->getMessage());
        }
    }

    // ─── CONTACT VALIDATION HELPERS ─────────────────────────────────

    /**
     * Detect the type of contact info from a standalone string.
     */
    private function detectContactType(string $value): ?string
    {
        $value = trim($value);

        // Email check
        if (preg_match('/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/', $value)) {
            return 'email';
        }

        // Phone check (international formats, 8-15 digits)
        if (preg_match('/^\+?[\d\s\-()]{8,20}$/', $value) && preg_match('/\d{7,}/', preg_replace('/\D/', '', $value))) {
            return 'phone';
        }

        // Username check (@handle format)
        if (preg_match('/^@[\w]{3,30}$/', $value)) {
            return 'username';
        }

        return null;
    }

    /**
     * Try to extract contact information from a message body.
     */
    private function extractContactFromMessage(string $message): ?array
    {
        // Try email first
        if (preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/', $message, $matches)) {
            return ['type' => 'email', 'value' => $matches[0]];
        }

        // Try phone number (various international formats)
        if (preg_match('/(\+?\d[\d\s\-()]{7,}\d)/', $message, $matches)) {
            $cleaned = preg_replace('/\D/', '', $matches[1]);
            if (strlen($cleaned) >= 8 && strlen($cleaned) <= 15) {
                return ['type' => 'phone', 'value' => $matches[1]];
            }
        }

        // Try @username
        if (preg_match('/@([\w]{3,30})/', $message, $matches)) {
            return ['type' => 'username', 'value' => '@' . $matches[1]];
        }

        return null;
    }
}
