<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all();
        $roles = ['admin', 'manager', 'technician', 'accounts', 'user'];
        return view('admin.users.users', compact('users', 'roles'));
    }

    /**
     * Assign a role to the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,manager,technician,accounts,user',
        ]);

        // Store null for 'user' role for clarity in your code
        $user->role = $request->role === 'user' ? null : $request->role;
        $user->save();

        return redirect()->route('admin.users.index')->with('message', 'Role updated!');
    }

    /**
     * Toggle activation status for the specified user.
     *
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActivation(User $user)
    {
        // Prevent self-deactivation
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('message', 'You cannot deactivate yourself.');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        return redirect()->back()->with('message', 'User activation status updated.');
    }

    /**
     * Show activation confirmation page via token.
     *
     * @param  string $token
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showActivationPage(string $token)
    {
        $user = User::where('activation_token', $token)
                    ->where('is_active', false)
                    ->first();

        if (! $user) {
            return redirect()->route('login')->with('message', 'Invalid or expired activation link.');
        }

        return view('admin.users.activate', compact('user', 'token'));
    }

    /**
     * Process user activation from token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processActivation(Request $request)
    {
        $request->validate([
            'token' => 'required|string|exists:users,activation_token',
        ]);

        $user = User::where('activation_token', $request->token)
                    ->where('is_active', false)
                    ->first();

        if (! $user) {
            return redirect()->route('login')->with('message', 'Invalid or expired activation token.');
        }

        $user->is_active = true;
        $user->activation_token = null; // Invalidate token after activation
        $user->save();

        return redirect()->route('login')->with('success', 'User account activated successfully. You can now log in.');
    }
}
