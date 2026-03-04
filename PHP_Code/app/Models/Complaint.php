<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DateFormatTrait;

class Complaint extends Model
{
    use HasFactory, SoftDeletes, DateFormatTrait;

    protected $table = 'complaints';

    protected $fillable = [
        'user_name',
        'contact_info',
        'contact_type',
        'message',
        'category',
        'status',
        'ip_address',
    ];

    public function getCreatedAtAttribute()
    {
        return $this->formatDateValue($this->getRawOriginal('created_at'));
    }

    public function getUpdatedAtAttribute()
    {
        return $this->formatDateValue($this->getRawOriginal('updated_at'));
    }

    /**
     * Get a human-readable status badge class.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->getRawOriginal('status') ?? $this->attributes['status'] ?? 'new') {
                'new' => 'badge-danger',
                'in_progress' => 'badge-warning',
                'resolved' => 'badge-success',
                default => 'badge-secondary',
            };
    }
}
