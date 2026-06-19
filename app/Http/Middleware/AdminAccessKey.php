<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccessKey
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->get('key') !== env('ADMIN_LOGIN_SLUG')) {
            abort(404);
        }
        return $next($request);
    }
}