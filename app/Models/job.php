<?php
// app/Models/Job.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Job extends Model
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
        'customer_address',
        'priority',
        'assigned_at',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'date_booked' => 'datetime',
        'date_resolved' => 'datetime',
        'time_start' => 'datetime',
        'time_finish' => 'datetime',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'amount_charged' => 'decimal:2',
        'billed_hours' => 'decimal:2'
    ];

    // Relationships
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForTechnician($query, $technicianId)
    {
        return $query->where('assigned_to', $technicianId);
    }

    // Mutators & Accessors
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'status-pending',
            'assigned' => 'status-open',
            'in_progress' => 'status-in_progress',
            'completed' => 'status-resolved',
            'cancelled' => 'priority-high',
            default => 'status-pending'
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            'low' => 'priority-low',
            'medium' => 'priority-medium',
            'high' => 'priority-high',
            'urgent' => 'priority-high',
            default => 'priority-medium'
        };
    }

    public function getDurationAttribute()
    {
        if ($this->time_start && $this->time_finish) {
            return $this->time_start->diffInMinutes($this->time_finish);
        }
        return null;
    }

    // Boot method for auto-generating job card
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->job_card)) {
                $job->job_card = self::generateJobCard();
            }
        });
    }

    private static function generateJobCard()
    {
        $prefix = 'JOB-' . date('Y') . '-';
        $lastJob = self::where('job_card', 'like', $prefix . '%')
                      ->orderBy('id', 'desc')
                      ->first();
        
        if ($lastJob) {
            $lastNumber = (int) substr($lastJob->job_card, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}
