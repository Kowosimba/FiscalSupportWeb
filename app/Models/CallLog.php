<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_card',
        'fault_description',
        'zimra_ref',
        'date_booked',
        'date_resolved',
        'time_start',
        'time_finish',
        'type',
        'billed_hours',
        'amount_charged',
        'status',
        'approved_by',
        'assigned_to',
        'engineer_comments',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address'
    ];

    protected $casts = [
        'date_booked' => 'date',
        'date_resolved' => 'date',
        'time_start' => 'datetime:H:i',
        'time_finish' => 'datetime:H:i',
        'billed_hours' => 'decimal:2',
        'amount_charged' => 'decimal:2'
    ];

    // Relationships
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopeForTechnician($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
