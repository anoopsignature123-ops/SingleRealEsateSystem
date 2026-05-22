<?php

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
                return route('associate-panel.login'); // Associate ko associate login par bhejo
            }

            return route('login'); // Admin/Web ko main login par bhejo
        });

        // 2. Agar login hone ke baad login page par jaye toh kahan redirect karna hai
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
