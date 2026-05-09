<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:pharmacien,preparateur,caissier,patient',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        return redirect()->route('login')->with('success', 'Inscription réussie. Veuillez vous connecter.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, false)) {
            // Regenerate the session ID after a successful login to prevent
            // session fixation attacks.
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Connexion réussie.');
        }

        // Use a generic error message to avoid leaking whether the email exists.
        return back()->withErrors(['email' => 'Identifiants invalides.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token on logout to
        // prevent session replay and CSRF token reuse.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}
