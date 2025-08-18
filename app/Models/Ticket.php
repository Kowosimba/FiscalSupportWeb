<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use App\Models\Comment;

class Ticket extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'tickets'; // If your table is named support_tickets

    protected $fillable = [
        'company_name',
        'contact_details',  // matches migration column
        'email',
        'subject',
        'message',
        'service',
        'status',
        'priority',
        'assigned_to',
        'attachment',
    ];


    protected $casts = [
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user assigned to this ticket
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the customer contact for this ticket
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerContact::class, 'customer_email', 'email');
    }

    /**
     * Get tickets by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get tickets by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Get unassigned tickets
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    /**
     * If you need assignedTechnician as an alias
     */

    /**
     * Add this only if you want comments functionality
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id');
    }

    /**
     * Alias for assignedTo relationship - for backward compatibility
     */
    public function assignedTechnician()
    {
        return $this->assignedTo();
    }
 
    // In your Ticket model
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
