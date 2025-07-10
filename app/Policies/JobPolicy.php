<?php
// app/Policies/CallLogPolicy.php

namespace App\Policies;

use App\Models\CallLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'accounts', 'engineer']);
    }

    public function view(User $user, CallLog $callLog)
    {
        if (in_array($user->role, ['admin', 'accounts'])) {
            return true;
        }
        
        return $user->role === 'engineer' && $callLog->engineer === $user->name;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'accounts']);
    }

    public function update(User $user, CallLog $callLog)
    {
        if (in_array($user->role, ['admin', 'accounts'])) {
            return true;
        }
        
        return $user->role === 'engineer' && $callLog->engineer === $user->name;
    }

    public function assign(User $user, CallLog $callLog)
    {
        return in_array($user->role, ['admin', 'accounts']);
    }

    public function updateStatus(User $user, CallLog $callLog)
    {
        if (in_array($user->role, ['admin', 'accounts'])) {
            return true;
        }
        
        return $user->role === 'engineer' && $callLog->engineer === $user->name;
    }

    public function delete(User $user, CallLog $callLog)
    {
        return $user->role === 'admin';
    }

    public function viewReports(User $user)
    {
        return $user->role === 'admin';
    }

    public function export(User $user)
    {
        return $user->role === 'admin';
    }

    public function complete(User $user, CallLog $callLog)
    {
        if (in_array($user->role, ['admin', 'accounts'])) {
            return true;
        }
        
        return $user->role === 'engineer' && $callLog->engineer === $user->name;
    }
}
