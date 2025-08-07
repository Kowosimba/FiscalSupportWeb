<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallLog extends Model
{
    use HasFactory;

    protected $fillable = [
    'customer_name', 'customer_email', 'customer_phone', 'customer_address',
    'zimra_ref', 'type', 'amount_charged', 'currency', 'date_booked',
    'fault_description', 'status', 'approved_by', 'approved_by_name',
    'booked_by', 'assigned_to'
];


    protected $casts = [
        'date_booked' => 'datetime',
        'date_resolved' => 'datetime',
        'time_start' => 'datetime',
        'time_finish' => 'datetime',
        'amount_charged' => 'float',
        // DO NOT cast billed_hours since it can be "10%", "2 hours", etc.
    ];

    // Relationships
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
    /**
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Ensure the value is numeric before querying
        if (!is_numeric($value)) {
            abort(404);
        }
        
        return $this->where('id', $value)->first() ?? abort(404);
    }

    // In App\Models\CallLog.php

/**
 * Get the hourly rate if billed_hours is numeric
 */
public function getHourlyRateAttribute()
{
    if (!$this->billed_hours || !$this->amount_charged) {
        return null;
    }
    
    // Check if billed_hours is numeric
    if (is_numeric($this->billed_hours) && $this->billed_hours > 0) {
        return $this->amount_charged / $this->billed_hours;
    }
    
    return null;
}

/**
 * Get formatted billed hours display
 */
public function getFormattedBilledHoursAttribute()
{
    if (!$this->billed_hours) {
        return 'N/A';
    }
    
    // If it's numeric, format as hours
    if (is_numeric($this->billed_hours)) {
        return $this->billed_hours . ' hours';
    }
    
    // Otherwise return as-is (for "10%" etc.)
    return $this->billed_hours;
}

/**
 * Check if billed hours is a percentage
 */
public function isBilledHoursPercentage()
{
    return $this->billed_hours && str_contains($this->billed_hours, '%');
}


    /**
     * Get the customer contact for this job
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerContact::class, 'customer_email', 'email');
    }

     /**
     * Get the user who approved this call log
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    /**
     * Scope for completed jobs
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'complete');
    }

    /**
     * Scope for pending jobs
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for in progress jobs
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
}
