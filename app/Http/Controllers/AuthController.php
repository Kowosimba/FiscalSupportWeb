<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('show.login')->with('success', 'Registration successful! You can log in now.');
    }

   public function login(Request $request)
{
    $validated = $request->validate([
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:8',
    ]);

    if (Auth::attempt($validated, $request->boolean('remember'))) {
        $request->session()->regenerate();
        
        // Use route name for redirect
        return redirect()->intended(route('admin.index'));
    }

    throw ValidationException::withMessages([
        'email' => 'The provided credentials do not match our records.',
    ]);
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Logout successful');
    }
}
