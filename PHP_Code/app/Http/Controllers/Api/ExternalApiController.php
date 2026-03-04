<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ResponseService;
use App\Services\UserService;
use App\Repositories\User\UserInterface;
use App\Repositories\Student\StudentInterface;
use App\Repositories\SessionYear\SessionYearInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;
use TypeError;
use App\Models\School;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ExternalApiController extends Controller
{
    private UserService $userService;
    private UserInterface $user;
    private StudentInterface $student;
    private SessionYearInterface $sessionYear;

    public function __construct(UserService $userService, UserInterface $user, StudentInterface $student, SessionYearInterface $sessionYear)
    {
        $this->userService = $userService;
        $this->user = $user;
        $this->student = $student;
        $this->sessionYear = $sessionYear;
    }

    public function storeStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $school_id = $request->school_id;
        $school = School::on('mysql')->find($school_id);

        if (!$school) {
            return response()->json([
                'error' => true,
                'message' => 'School not found',
            ], 404);
        }

        // Switch to the school's database
        DB::setDefaultConnection('school');
        Config::set('database.connections.school.database', $school->database_name);
        DB::purge('school');
        DB::connection('school')->reconnect();
        DB::setDefaultConnection('school');

        $payloadValidator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|digits_between:6,15',
            'image' => 'nullable|mimes:jpeg,png,jpg,svg|image|max:2048',
            'dob' => 'required',
            'class_id' => 'required|numeric|exists:classes,id',
            'admission_no' => 'required|unique:users,email',
            'admission_date' => 'required',
            'session_year_id' => 'required|numeric|exists:session_years,id',
            'guardian_email' => 'required|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'guardian_first_name' => 'required|string',
            'guardian_last_name' => 'required|string',
            'guardian_mobile' => 'required|numeric|digits_between:6,15',
            'guardian_gender' => 'required|in:male,female',
            'guardian_image' => 'nullable|mimes:jpg,jpeg,png|max:4096',
            'gender' => 'required|in:male,female',
        ], [
            'guardian_email.regex' => 'Please enter a valid guardian email (e.g. user@example.com).',
        ]);

        if ($payloadValidator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation error',
                'data' => $payloadValidator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get the user details from the guardian details & identify whether that user is guardian or not.
            $guardianUser = $this->user->builder()->whereHas('roles', function ($q) {
                $q->where('name', '!=', 'Guardian');
            })->where('email', $request->guardian_email)->withTrashed()->first();

            if ($guardianUser) {
                ResponseService::errorResponse("Email ID is already taken for Other Role");
            }

            $sessionYear = $this->sessionYear->findById($request->session_year_id);
            if (!$sessionYear) {
                ResponseService::errorResponse("Invalid session year ID.");
            }

            $guardian = $this->userService->createOrUpdateParent(
                $request->guardian_first_name,
                $request->guardian_last_name,
                $request->guardian_email,
                $request->guardian_mobile,
                $request->guardian_gender,
                $request->guardian_image,
                null,
                $school_id
            );

            $is_send_notification = false; // Set to false to avoid Auth::user() calls inside sendRegistrationEmail

            $user = $this->userService->createStudentUser(
                $request->first_name,
                $request->last_name,
                $request->admission_no,
                $request->mobile,
                $request->dob,
                $request->gender,
                $request->image,
                null,
                $request->admission_date,
                $request->current_address,
                $request->permanent_address,
                $sessionYear->id,
                $guardian->id,
                $request->extra_fields ?? [],
                0,
                $is_send_notification,
                $school_id
            );

            $this->student->builder()->where('user_id', $user->id)->update([
                'class_id' => $request->class_id,
                'class_section_id' => null,
                'application_type' => 'online',
                'application_status' => 0
            ]);

            DB::commit();
            ResponseService::successResponse('Data Stored Successfully');
        }
        catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "ExternalApiController -> storeStudent method");
            ResponseService::errorResponse();
        }
    }
}
