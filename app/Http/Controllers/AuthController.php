<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Adresse e-mail ou mot de passe incorrect.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->account_status !== 'active') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Votre compte n’est pas encore activé. '
                    .'Veuillez attendre la validation de votre demande.',
            ])->onlyInput('email');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'owner') {
            return redirect()->route('pharmacien.dashboard');
        }

        Auth::logout();

        return back()->withErrors([
            'email' => 'Vous ne disposez pas d’un accès à cet espace.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
