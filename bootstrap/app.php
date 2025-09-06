<?php // Pastikan ini di baris paling atas jika belum ada

use Filament\Facades\Filament;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php', 
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add this line to direct guests to Filament's login page
        $middleware->redirectTo(
            guests: fn() => Filament::getLoginUrl()
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
