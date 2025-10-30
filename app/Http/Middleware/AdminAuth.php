<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        // Cek apakah user sudah login (ada token)
        if (!Session::has('api_token')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah user memiliki role admin
        $user = Session::get('user');

        if (!$user || !isset($user['role']) || $user['role'] !== 'admin') {
            // Hapus session dan redirect
            Session::flush();
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Hanya admin yang diizinkan.');
        }

        return $next($request);
    }
}
