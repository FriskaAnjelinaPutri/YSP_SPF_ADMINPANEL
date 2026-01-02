<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AdminUserController extends Controller
{
    private function apiBase()
    {
        return env('API_URL', 'http://127.0.0.1:8000/api');
    }

    private function token()
    {
        return Session::get('api_token');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/users', [
                    'search' => $search,
                ]);

            if ($response->successful()) {
                $users = $response->json()['data'] ?? [];

                return view('user.index', compact('users', 'search'));
            }

            return back()->with('error', 'Gagal mengambil data pengguna dari API.');
        } catch (\Exception $e) {
            Log::error('Error fetching users: '.$e->getMessage());

            return back()->with('error', 'Tidak dapat terhubung ke server API.');
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withToken($this->token())->get($this->apiBase().'/users/'.$id);

            if ($response->successful()) {
                $responseData = $response->json();
                $user = $responseData['data'] ?? $responseData ?? null;

                if ($user) {
                    return view('user.edit', compact('user'));
                }

                return back()->with('error', 'Struktur data pengguna dari API tidak valid.');
            }

            return back()->with('error', 'Gagal mengambil data pengguna.');
        } catch (\Exception $e) {
            Log::error('Error fetching user for edit: '.$e->getMessage());

            return back()->with('error', 'Tidak dapat terhubung ke server API.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,karyawan',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        try {
            $response = Http::withToken($this->token())->put($this->apiBase().'/users/'.$id, $data);

            if ($response->successful()) {
                return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
            }

            return back()->with('error', 'Gagal memperbarui data pengguna: '.$response->json('message', 'Unknown error'))->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating user: '.$e->getMessage());

            return back()->with('error', 'Tidak dapat terhubung ke server API.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->token())->delete($this->apiBase().'/users/'.$id);

            if ($response->successful()) {
                return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
            }

            return back()->with('error', 'Gagal menghapus pengguna: '.$response->json('message', 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Error deleting user: '.$e->getMessage());

            return back()->with('error', 'Tidak dapat terhubung ke server API.');
        }
    }
}
