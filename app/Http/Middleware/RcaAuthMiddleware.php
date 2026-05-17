<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RcaAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('rca_logged_in') || !session('rca_api_token')) {
            return redirect()->route('rca.login');
        }

        return $next($request);
    }
}