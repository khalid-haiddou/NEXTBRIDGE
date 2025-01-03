<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Show login/register view
    public function showLoginRegister()
    {
        return view('login');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'first_name' => $request->input('name'),
            'last_name' => $request->input('last-name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'user', // Default role for new users
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully! Please log in.');
    }

    // Handle login
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Redirect the user to the appropriate page
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard'); // Admin goes to the dashboard
        } else {
            return redirect()->route('index'); // Regular users go to the index page
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout(); // Logs out the user
        $request->session()->invalidate(); // Invalidates the session
        $request->session()->regenerateToken(); // Regenerates the CSRF token

        return redirect()->route('login')->with('message', 'You have been logged out successfully.');
    }
}