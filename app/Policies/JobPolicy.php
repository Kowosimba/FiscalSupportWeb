<?php
// app/Policies/JobPolicy.php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'manager', 'accountant', 'technician']);
    }

    public function view(User $user, Job $job)
    {
        if (in_array($user->role, ['admin', 'manager', 'accountant'])) {
            return true;
        }
        
        return $user->role === 'technician' && $job->assigned_to === $user->id;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'manager', 'accountant']);
    }

    public function update(User $user, Job $job)
    {
        if (in_array($user->role, ['admin', 'manager', 'accountant'])) {
            return true;
        }
        
        return $user->role === 'technician' && $job->assigned_to === $user->id;
    }

    public function assign(User $user, Job $job)
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function updateStatus(User $user, Job $job)
    {
        return $user->role === 'technician' && $job->assigned_to === $user->id;
    }

    public function delete(User $user, Job $job)
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
