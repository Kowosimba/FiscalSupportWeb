<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ticket extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'company_name',
        'contact_details',
        'email',
        'subject',
        'message',
        'service',
        'priority',
        'status',
        'assigned_to',
        'attachment',
    ];
  
 public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

     protected $attributes = [
        'priority' => 'low', // default priority
    ];

    public function comments()
{
    return $this->hasMany(Comment::class);
}

  public function assigned_to_user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

public function routeNotificationForMail($notification)
{
    return $this->email;
}

    
}