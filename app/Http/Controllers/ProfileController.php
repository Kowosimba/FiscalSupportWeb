<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    // Apply 'auth' middleware in routes/web.php or using protected $middleware if needed
    // No constructor needed as the base Controller does not define one

    /**
     * Show the user's profile page
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get recent login sessions (you might want to create a sessions table for this)
        $recentSessions = $this->getRecentSessions();
        
        return view('admin.profile.show', compact('user', 'recentSessions'));
    }

    /**
     * Update the user's profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required|string',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update user information
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        Log::info('User profile updated', [
            'user_id' => $user->id,
            'changes' => $request->only(['name', 'email'])
        ]);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        Log::info('User password updated', ['user_id' => $user->id]);

        return redirect()->route('profile.show')->with('success', 'Password updated successfully!');
    }

    /**
     * Update the user's avatar
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Update user avatar path
            $user->update(['avatar' => $avatarPath]);

            Log::info('User avatar updated', ['user_id' => $user->id, 'avatar_path' => $avatarPath]);

            return redirect()->route('profile.show')->with('success', 'Avatar updated successfully!');
        } catch (\Exception $e) {
            Log::error('Avatar upload failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('profile.show')->with('error', 'Failed to upload avatar. Please try again.');
        }
    }

    /**
     * Delete the user's avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();
        
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
            
            Log::info('User avatar deleted', ['user_id' => $user->id]);
        }

        return redirect()->route('profile.show')->with('success', 'Avatar removed successfully!');
    }

    /**
     * Get recent login sessions (mock data for now)
     * You might want to implement proper session tracking
     */
    private function getRecentSessions()
    {
        // This is mock data. In a real application, you'd track sessions in a database
        return [
            [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'location' => 'Current Session',
                'last_activity' => now(),
                'is_current' => true,
            ],
            // Add more mock sessions or implement real session tracking
        ];
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'theme' => 'nullable|in:light,dark,auto',
            'language' => 'nullable|in:en,es,fr,de',
            'timezone' => 'nullable|string|max:50',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
        ]);

        // You might want to store preferences in a separate table or JSON column
        // For now, let's add these fields to the users table migration
        $user->update([
            'preferences' => json_encode($validated)
        ]);

        Log::info('User preferences updated', [
            'user_id' => $user->id,
            'preferences' => $validated
        ]);

        return redirect()->route('profile.show')->with('success', 'Preferences updated successfully!');
    }
}