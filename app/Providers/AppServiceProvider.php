<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Broadcast::routes(['middleware' => ['web', 'auth']]);

        // require base_path('routes/channels.php');

        View::composer('*', function ($view) {
            if (!Auth::check() && session()->has('impersonate_user_id')) {
                $user = \App\Models\User::find(session('impersonate_user_id'));
                Auth::login($user);
            }
        });
    }
}
