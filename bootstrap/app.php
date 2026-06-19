<?php

use App\Http\Middleware\AdminAccessKey;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('associate-panel*')) {
                return route('associate-panel.login');
            }

            return route('login');
        });

        $middleware->alias([
            'admin.key' => AdminAccessKey::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('associate-panel*')) {
                return route('associate-panel.dashboard');
            }

            return route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();