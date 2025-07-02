<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function showRegister()
    {
        return view('auth.register');
    }
    public function showLogin()
    {
        return view('auth.login');
    }

    public function Register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
            $user = User::create($validated);
            
            Auth::login($user);
            return redirect()->route('register')->with('success', 'Registration successful you can log in now!');
    }


    public function Login(Request $request)
    {
        $validated = request()->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        if (Auth::attempt($validated)) {
            $request ->session()->regenerate();
            return redirect()->route('admin.index')->with('success', 'Login successful');
        } ;
        
           throw ValidationException::withMessages([
            'credentials' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('show.login')->with('success', 'Logout successful');
    }
}
