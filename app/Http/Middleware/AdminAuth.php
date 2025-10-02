<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('api_token')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
