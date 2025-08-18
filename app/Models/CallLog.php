<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class CallLog extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'job_card',
        'customer_name', 
        'customer_email', 
        'customer_phone', 
        'customer_address',
        'zimra_ref', 
        'type', 
        'amount_charged', 
        'currency', 
        'date_booked',
        'date_resolved',
        'time_start',
        'time_finish',
        'fault_description', 
        'status', 
        'approved_by', 
        'approved_by_name',
        'booked_by', 
        'assigned_to',
        'engineer_comments',
        'billed_hours',
        'assigned_at'
    ];

    protected $casts = [
        'date_booked' => 'datetime',
        'date_resolved' => 'datetime',
        'time_start' => 'datetime',
        'time_finish' => 'datetime',
        'amount_charged' => 'float',
        'assigned_at' => 'datetime',
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

    /**
     * Company name is the same as customer name
     */
    public function getCompanyNameAttribute()
    {
        return $this->customer_name;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (!is_numeric($value)) {
            abort(404);
        }
        
        return $this->where('id', $value)->first() ?? abort(404);
    }

    /**
     * Get the hourly rate if billed_hours is numeric
     */
    public function getHourlyRateAttribute()
    {
        if (!$this->billed_hours || !$this->amount_charged) {
            return null;
        }
        
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
        
        if (is_numeric($this->billed_hours)) {
            return $this->billed_hours . ' hours';
        }
        
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

    /**
     * Get formatted time start
     */
    public function getFormattedTimeStartAttribute()
    {
        if (!$this->time_start) {
            return 'N/A';
        }
        
        try {
            return \Carbon\Carbon::parse($this->time_start)->format('H:i');
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Get formatted time finish
     */
    public function getFormattedTimeFinishAttribute()
    {
        if (!$this->time_finish) {
            return 'N/A';
        }
        
        try {
            return \Carbon\Carbon::parse($this->time_finish)->format('H:i');
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}