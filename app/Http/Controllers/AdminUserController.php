<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    // Show all users and their roles
    public function index()
    {
        $users = User::orderBy('name')->get();
        $roles = ['admin', 'manager', 'technician', 'accounts', 'user'];
        return view('admin.users.users', compact('users', 'roles'));
    }

    // Assign a role to a user
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,manager,technician,accounts,user',
        ]);
        $user->role = $request->role === 'user' ? null : $request->role; // null means plain user
        $user->save();

        return redirect()->route('admin.users.index')->with('message', 'Role updated!');
    }
}
