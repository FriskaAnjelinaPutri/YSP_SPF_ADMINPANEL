<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            'password' => 'required|min:6',
        ]);

        try {
            // Panggil API login
            $response = Http::post(env('API_URL').'/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Cek jika request gagal (HTTP error)
            if ($response->failed()) {
                return back()->withInput($request->only('email'))->with('error', 'Login gagal. Periksa email dan password Anda.');
            }

            $data = $response->json();

            // Pastikan login sukses
            if (! ($data['success'] ?? false)) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', $data['message'] ?? 'Login gagal, coba lagi.');
            }

            // Ambil token dan user dari response
            $token = $data['data']['access_token'] ?? null;
            $user = $data['data']['user'] ?? null;

            // Validasi token dan user
            if (! $token || ! $user) {
                return back()->withInput($request->only('email'))->with('error', 'Login gagal, token atau data user tidak tersedia.');
            }

            // Cek role - Hanya admin yang boleh login
            if (! isset($user['role']) || $user['role'] !== 'admin') {
                return back()->withInput($request->only('email'))->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses panel ini.');
            }

            // Simpan ke session
            Session::put('api_token', $token);
            Session::put('user', $user);

            // Log aktivitas (opsional)
            Log::info('Admin login berhasil', [
                'email' => $user['email'] ?? 'unknown',
                'role' => $user['role'] ?? 'unknown',
            ]);

            // Redirect ke dashboard atau halaman sebelumnya
            return redirect()
                ->intended(route('dashboard'))
                ->with('success', 'Selamat datang, '.($user['name'] ?? 'Admin').'!');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Error koneksi ke API
            Log::error('Login API connection error', ['error' => $e->getMessage()]);

            return back()->withInput($request->only('email'))->with('error', 'Tidak dapat terhubung ke server. Silakan coba lagi.');
        } catch (\Exception $e) {
            // Error umum lainnya
            Log::error('Login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput($request->only('email'))->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
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
