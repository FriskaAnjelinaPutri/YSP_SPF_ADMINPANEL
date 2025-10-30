<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    private $apiBase;

    public function __construct()
    {
        $this->apiBase = env('API_URL', 'http://127.0.0.1:8000/api');
    }

    private function token()
    {
        return session('api_token');
    }

    /**
     * Menampilkan daftar jadwal
     */
    public function index()
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Sesi login habis. Silakan login ulang.');
        }

        $response = Http::withToken($this->token())->get("{$this->apiBase}/jadwal");

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
    }

    /**
     * Tampilkan form tambah jadwal
     */
    public function create()
    {
        return view('jadwal.create');
    }

    /**
     * Simpan data jadwal baru ke API
     */
    public function store(Request $request)
    {
        Log::info('Form Jadwal diterima:', $request->all());

        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $payload = $request->only(['jadwal_nama', 'jam_mulai', 'jam_selesai', 'status']);

        $response = Http::withToken($this->token())
            ->timeout(10)
            ->post("{$this->apiBase}/jadwal", $payload);

        Log::info('API Jadwal Store Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->failed()) {
            $errorMsg = $response->json()['message'] ?? 'Gagal menambah jadwal';
            Log::error('API Jadwal Store Error: ' . $errorMsg);
            return back()->withInput()->with('error', $errorMsg);
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Edit jadwal
     */
    public function edit($kode)
    {
        $response = Http::withToken($this->token())->get("{$this->apiBase}/jadwal/{$kode}");

        if ($response->failed()) {
            return redirect()->route('jadwal.index')->with('error', 'Gagal mengambil data jadwal.');
        }

        $jadwal = (object) ($response->json()['data'] ?? []);

        return view('jadwal.edit', compact('jadwal'));
    }

    /**
     * Update jadwal
     */
    public function update(Request $request, $kode)
    {
        $request->validate([
            'jadwal_nama' => 'required|string|max:100',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'status' => 'required|in:Y,T',
        ]);

        $payload = $request->only(['jadwal_nama', 'jam_mulai', 'jam_selesai', 'status']);

        $response = Http::withToken($this->token())
            ->asForm()
            ->post("{$this->apiBase}/jadwal/{$kode}", array_merge($payload, ['_method' => 'PUT']));

        if ($response->failed()) {
            Log::error('Gagal update jadwal', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return back()->withInput()->with('error', 'Gagal memperbarui jadwal.');
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal
     */
    public function destroy($kode)
    {
        $response = Http::withToken($this->token())->delete("{$this->apiBase}/jadwal/{$kode}");

        if ($response->failed()) {
            Log::error('Gagal hapus jadwal', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return back()->with('error', 'Gagal menghapus jadwal.');
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
