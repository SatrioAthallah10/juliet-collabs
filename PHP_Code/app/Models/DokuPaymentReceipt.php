<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\DateFormatTrait;

class DokuPaymentReceipt extends Model
{
    use HasFactory, DateFormatTrait;

    protected $connection = 'mysql';

    protected $fillable = [
        'school_id',
        'school_inquiry_id',
        'invoice_number',
        'amount',
        'payment_status',
        'payment_gateway',
        'payment_date',
        'doku_transaction_id',
        'raw_payload',
        'package_name',
        'school_name',
        'school_email',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Scope: Super Admin sees all, School Admin sees only their own.
     */
    public function scopeOwner($query)
    {
        if (Auth::user()) {
            if (Auth::user()->hasRole('Super Admin')) {
                return $query; // Super Admin can see all receipts
            }

            if (!empty(Auth::user()->school_id)) {
                return $query->where('school_id', Auth::user()->school_id);
            }
        }

        // Failsafe: if not Super Admin and no valid school_id, deny all access
        return $query->whereRaw('1 = 0');
    }

    /**
     * Get the school that owns this receipt.
     */
    public function school()
    {
        return $this->belongsTo(School::class)->withTrashed();
    }

    /**
     * Get the inquiry that this receipt was created from.
     */
    public function inquiry()
    {
        return $this->belongsTo(SchoolInquiry::class , 'school_inquiry_id');
    }

    public function getCreatedAtAttribute()
    {
        return $this->formatDateValue($this->getRawOriginal('created_at'));
    }

    public function getUpdatedAtAttribute()
    {
        return $this->formatDateValue($this->getRawOriginal('updated_at'));
    }
}
