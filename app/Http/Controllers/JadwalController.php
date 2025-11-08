<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class JadwalController extends Controller
{
    private function apiBase()
    {
        return env('API_URL', 'http://127.0.0.1:8000/api');
    }

    private function token()
    {
        return Session::get('api_token');
    }

    /**
     * Menampilkan daftar jadwal
     */
    public function index()
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Sesi login habis. Silakan login ulang.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/jadwal');

            if ($response->failed()) {
                Log::error('Gagal ambil data jadwal', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return view('jadwal.index', [
                    'jadwals' => [],
                    'error' => 'Gagal mengambil data jadwal dari API',
                ]);
            }

            $data = $response->json()['data'] ?? [];
            $jadwals = collect($data);

            return view('jadwal.index', compact('jadwals'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data jadwal', ['error' => $e->getMessage()]);
            return view('jadwal.index', [
                'jadwals' => [],
                'error' => 'Terjadi kesalahan saat mengambil data',
            ]);
        }
    }

    /**
     * Tampilkan form tambah jadwal
     */
    public function create()
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        return view('jadwal.create');
    }

    /**
     * Simpan data jadwal baru ke API
     */
    public function store(Request $request)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'jadwal_nama' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'status' => 'required|boolean',
        ], [
            'jadwal_nama.required' => 'Nama jadwal wajib diisi',
            'jam_mulai.required' => 'Jam mulai wajib diisi',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid',
            'jam_selesai.required' => 'Jam selesai wajib diisi',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid',
            'status.required' => 'Status wajib dipilih',
        ]);

        try {
            // Tambahkan :00 untuk detik
            $payload = [
                'jadwal_nama' => $request->jadwal_nama,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'status' => $request->status,
            ];

            Log::info('Jadwal Store Payload:', $payload);

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->timeout(30)
                ->post($this->apiBase() . '/jadwal', $payload);

            Log::info('Jadwal Store Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Cek apakah response sesuai format yang diharapkan
                if (isset($responseData['success']) && $responseData['success']) {
                    return redirect()->route('jadwal.index')
                        ->with('success', 'Jadwal berhasil ditambahkan');
                }

                // Jika response tidak sesuai format
                Log::warning('Response format tidak sesuai:', $responseData);
            }

            $errorMsg = $response->json()['message'] ?? 'Gagal menambah jadwal. Response: ' . $response->body();
            return back()->withInput()->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan jadwal', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Edit jadwal
     */
    public function edit($kode)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/jadwal/' . $kode);

            if ($response->failed()) {
                Log::error('Gagal mengambil data jadwal', [
                    'kode' => $kode,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return redirect()->route('jadwal.index')
                    ->with('error', 'Gagal mengambil data jadwal.');
            }

            $data = $response->json();
            $jadwal = (object) ($data['data'] ?? []);

            return view('jadwal.edit', compact('jadwal'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil jadwal untuk edit', ['error' => $e->getMessage()]);
            return redirect()->route('jadwal.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Update jadwal
     */
    public function update(Request $request, $kode)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'jadwal_nama' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'status' => 'required|boolean',
        ], [
            'jadwal_nama.required' => 'Nama jadwal wajib diisi',
            'jam_mulai.required' => 'Jam mulai wajib diisi',
            'jam_mulai.date_format' => 'Format jam mulai tidak valid',
            'jam_selesai.required' => 'Jam selesai wajib diisi',
            'jam_selesai.date_format' => 'Format jam selesai tidak valid',
            'status.required' => 'Status wajib dipilih',
        ]);

        try {
            // Tambahkan :00 untuk detik
            $payload = [
                'jadwal_nama' => $request->jadwal_nama,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'status' => $request->status,
            ];

            Log::info('Jadwal Update Payload:', [
                'kode' => $kode,
                'payload' => $payload
            ]);

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->timeout(30)
                ->put($this->apiBase() . '/jadwal/' . $kode, $payload);

            Log::info('Jadwal Update Response:', [
                'kode' => $kode,
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Cek apakah response sesuai format yang diharapkan
                if (isset($responseData['success']) && $responseData['success']) {
                    return redirect()->route('jadwal.index')
                        ->with('success', 'Jadwal berhasil diperbarui');
                }

                // Jika response tidak sesuai format
                Log::warning('Response format tidak sesuai:', $responseData);
            }

            $errorMsg = $response->json()['message'] ?? 'Gagal memperbarui jadwal. Response: ' . $response->body();
            return back()->withInput()->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('Error saat update jadwal', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus jadwal
     */
    public function destroy($kode)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->delete($this->apiBase() . '/jadwal/' . $kode);

            Log::info('Jadwal Delete Response:', [
                'kode' => $kode,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                return redirect()->route('jadwal.index')
                    ->with('success', 'Jadwal berhasil dihapus.');
            }

            $errorMsg = $response->json()['message'] ?? 'Gagal menghapus jadwal';
            return back()->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('Error saat menghapus jadwal', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
}
