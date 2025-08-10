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
    /**
     * Show the user's profile page
     */
    public function show()
    {
        $user = Auth::user();
        $recentSessions = $this->getRecentSessions();
        
        return view('admin.profile.show', compact('user', 'recentSessions'));
    }

    /**
     * Update the user's profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'current_password' => 'required|string',
            ]);

            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                Log::warning('Failed profile update - wrong current password', [
                    'user_id' => $user->id,
                    'ip' => request()->ip()
                ]);
                
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
            
        } catch (ValidationException $e) {
            return redirect()->route('profile.show')->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('profile.show')->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        try {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
            ]);

            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                Log::warning('Failed password change attempt - wrong current password', [
                    'user_id' => $user->id,
                    'ip' => request()->ip()
                ]);
                
                throw ValidationException::withMessages([
                    'current_password' => 'The current password is incorrect.',
                ]);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            Log::info('User password updated successfully', ['user_id' => $user->id]);

            return redirect()->route('profile.show')->with('success', 'Password updated successfully!');
            
        } catch (ValidationException $e) {
            return redirect()->route('profile.show')->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Password update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('profile.show')->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * Update the user's avatar
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        
        try {
            $validated = $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

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
                'error' => $e->getMessage(),
                'file_info' => $request->file('avatar') ? [
                    'name' => $request->file('avatar')->getClientOriginalName(),
                    'size' => $request->file('avatar')->getSize(),
                    'mime' => $request->file('avatar')->getMimeType(),
                ] : 'No file'
            ]);

            return redirect()->route('profile.show')->with('error', 'Failed to upload avatar: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();
        
        try {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $user->update(['avatar' => null]);
                
                Log::info('User avatar deleted', ['user_id' => $user->id]);
            }

            return redirect()->route('profile.show')->with('success', 'Avatar removed successfully!');
            
        } catch (\Exception $e) {
            Log::error('Avatar deletion failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('profile.show')->with('error', 'Failed to remove avatar. Please try again.');
        }
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        try {
            $validated = $request->validate([
                'theme' => 'nullable|in:light,dark,auto',
                'language' => 'nullable|in:en,es,fr,de',
                'timezone' => 'nullable|string|max:50',
                'email_notifications' => 'boolean',
                'push_notifications' => 'boolean',
            ]);

            $user->update([
                'preferences' => $validated
            ]);

            Log::info('User preferences updated', [
                'user_id' => $user->id,
                'preferences' => $validated
            ]);

            return redirect()->route('profile.show')->with('success', 'Preferences updated successfully!');
            
        } catch (\Exception $e) {
            Log::error('Preferences update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('profile.show')->with('error', 'Failed to update preferences. Please try again.');
        }
    }

    /**
     * Get recent login sessions (mock data for now)
     */
    private function getRecentSessions()
    {
        return [
            [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'location' => 'Current Session',
                'last_activity' => now(),
                'is_current' => true,
            ],
        ];
    }
}