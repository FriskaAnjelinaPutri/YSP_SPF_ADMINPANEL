<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Panggil API login
        $response = Http::post(env('API_URL') . '/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $data = $response->json();

        // Cek apakah ada token
        if (!isset($data['data']['access_token'])) {
            return back()->with('error', $data['message'] ?? 'Login gagal, coba lagi.');
        }

        // Simpan token & user ke session
        session([
            'api_token' => $data['data']['access_token'],
            'user' => $data['data']['user']
        ]);

        // Redirect ke halaman yang diminta sebelumnya, atau ke dashboard
        return redirect()->intended(route('dashboard'));
    }

    public function logout()
    {
        session()->forget(['api_token', 'user']);
        return redirect()->route('login');
    }
}
