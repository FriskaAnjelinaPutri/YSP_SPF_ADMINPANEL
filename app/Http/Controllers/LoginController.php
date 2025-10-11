<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        try {
            // Panggil API login
            $response = Http::post(env('API_URL') . '/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $data = $response->json();

            // Debug: cek struktur response
            // dd($data);

            // Pastikan login sukses
            if (!($data['success'] ?? false)) {
                return back()->with('error', $data['message'] ?? 'Login gagal, coba lagi.');
            }

            // Ambil token dan user dari response
            $token = $data['data']['access_token'] ?? null;
            $user  = $data['data']['user'] ?? null;

            if (!$token || !$user) {
                return back()->with('error', 'Login gagal, token atau data user tidak tersedia.');
            }

            // Simpan ke session
            Session::put('api_token', $token);
            Session::put('user', $user);

            // Redirect ke dashboard atau halaman sebelumnya
            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            // Tangani error koneksi/API
            return back()->with('error', 'Gagal menghubungi server, silakan coba lagi.');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Hapus session
        Session::forget(['api_token', 'user']);

        return redirect()->route('login');
    }
}
