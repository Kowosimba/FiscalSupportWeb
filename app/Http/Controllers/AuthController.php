<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NewUserRegistered;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.index');
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('admin.index');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        Log::info('Registration process started');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,accounts,technician,user,manager',
        ]);

        Log::info('Validation passed', ['email' => $validated['email'], 'role' => $validated['role']]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Normalize role
        if ($validated['role'] === 'user') {
            $validated['role'] = null;
        }

        // Add activation fields BEFORE creating the user
        $validated['is_active'] = false;
        $validated['activation_token'] = Str::random(60);

        Log::info('User data prepared for creation', [
            'email' => $validated['email'],
            'role' => $validated['role'],
            'activation_token' => $validated['activation_token']
        ]);

        try {
            // Create user once; now $user has activation_token saved
            $user = User::create($validated);
            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'activation_token' => $user->activation_token
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'email' => $validated['email']
            ]);
            throw $e;
        }

        // Verify user has activation token after creation
        if (empty($user->activation_token)) {
            Log::error('User created but activation token is missing', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }

        // Get admin email
        $adminEmail = config('mail.admin_email', 'supporthre2@fiscalsupportservices.com');
        Log::info('Preparing to send email', [
            'admin_email' => $adminEmail,
            'user_email' => $user->email
        ]);

        try {
            // Send mail to admin with the user that has activation_token set
            Log::info('Attempting to send email');
            Mail::to($adminEmail)->send(new NewUserRegistered($user));
            Log::info('Email sent successfully');
        } catch (\Exception $e) {
            Log::error('Failed to send email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_email' => $adminEmail,
                'user_email' => $user->email
            ]);
            
            // Don't fail the registration if email fails, just log it
            Log::warning('Registration completed but email notification failed');
        }

        return redirect()->route('show.login')->with('success', 'Registration successful! Your account requires activation by an administrator before you can log in.');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'remember' => 'boolean'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Your account has not been activated yet. Please wait for admin approval.',
            ]);
        }

        // Handle Remember Me functionality
        $remember = $request->has('remember') && $request->boolean('remember');

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
            $request->session()->regenerate();
            
            // Log successful login
            Log::info('User logged in successfully', [
                'user_id' => Auth::id(),
                'email' => $validated['email'],
                'remember' => $remember ? 'yes' : 'no',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Set remember token if remember me is checked
            if ($remember) {
                $user = Auth::user();
                $user->setRememberToken(Str::random(60));
                $user->save();
                Log::info('Remember token set for user', ['user_id' => $user->id]);
            }

            return redirect()->intended(route('admin.index'));
        }

        Log::warning('Failed login attempt', [
            'email' => $validated['email'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Log::info('User logging out', ['user_id' => Auth::id()]);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    // Password Reset Methods
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if user exists and is active
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Your account is not activated. Please contact an administrator.']);
        }

        // Send password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Log::info('Password reset link sent', ['email' => $request->email]);
            return back()->with('status', 'We have emailed your password reset link!');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                Log::info('Password reset successfully', ['user_id' => $user->id]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('show.login')->with('success', 'Your password has been reset successfully!');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}