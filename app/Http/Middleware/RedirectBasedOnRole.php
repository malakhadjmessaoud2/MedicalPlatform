<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Contracts\Container\BindingResolutionException)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Si l'utilisateur est connecté et accède à /dashboard
        if (Auth::check() && $request->is('dashboard')) {
            $user = Auth::user();

            // Rediriger selon le rôle
            if ($user->role === 'medecin') {
                return redirect('/medecin/dashboard');
            } elseif ($user->role === 'patient') {
                return redirect('/patient/dashboard');
            } elseif ($user->role === 'operateurpharmacie') {
                return redirect('/pharmacie/dashboard');
            } elseif ($user->role === 'donateur') {
                return redirect('/donateur/dashboard');
            }
        }

        return $next($request);
    }
}
