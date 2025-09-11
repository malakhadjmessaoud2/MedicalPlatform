<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;

class AuthenticatedSessionController extends FortifyAuthenticatedSessionController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateLogin($request); // Ensure this method is defined

        // Attempt to log the user in
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
             $request->session()->regenerate();

            // Vérifier l'activation du compte pour les médecins
            $user = Auth::user();
            if ($user->role === 'medecin' && !$user->isActive) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('activation.pending')
                    ->withErrors(['email' => "Votre compte médecin est en attente d'activation par l'administrateur."])
                    ->with('status', "Votre compte médecin est en attente d'activation par l'administrateur.");
            }

            // Redirect based on user role
            return redirect()->intended($this->getHomePath($user));
        }

        // If authentication fails, redirect back
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Validate the user's login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the home path based on the authenticated user's role.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    protected function getHomePath($user)
    {
        if ($user->role === 'medecin') {
            return '/medecin/dashboard';
        } elseif ($user->role === 'patient') {
            return '/patient/dashboard';
        } elseif ($user->role === 'operateurpharmacie') {
            return '/pharmacie/dashboard';
        } elseif ($user->role === 'donateur') {
            return '/donateur/dashboard';
        } elseif ($user->role === 'admin') {
            return '/admin/dashboard';
        }

        // Default fallback
        return '/dashboard';
    }
}
