<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
    ];
    public function role()
        {
            return $this->role;
        }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function routeNotificationForMail()
{
    return $this->email; // Or use a different field if you have notification_email
}
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function assignedJobs()
{
    return $this->hasMany(CallLog::class, 'assigned_to');
}

public function approvedJobs()
{
    return $this->hasMany(CallLog::class, 'approved_by');
}

public function isTechnician()
{
    return $this->role === 'technician';
}

public function isAdmin()
{
    return $this->role === 'admin';
}

public function isAccounts()
{
    return $this->role === 'accounts';
}

    
}

