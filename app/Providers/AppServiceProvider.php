<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Tambahkan baris ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tambahkan 3 baris ini untuk memaksa HTTPS di Vercel
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}