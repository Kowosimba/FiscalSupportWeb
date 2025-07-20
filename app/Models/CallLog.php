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
        'approved_by_name',
        'assigned_to',
        'engineer_comments',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address'
    ];

    protected $casts = [
    'date_booked' => 'datetime',
    'date_resolved' => 'datetime',
    'time_start' => 'datetime',
    'time_finish' => 'datetime',
    'billed_hours' => 'decimal:2',
    'amount_charged' => 'decimal:2'
];

    // Relationships

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

    // app/Models/CallLog.php

public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}
    
}
