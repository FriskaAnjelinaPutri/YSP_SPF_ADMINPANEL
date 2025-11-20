<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PolaController extends Controller
{
    private function token()
    {
        return session('api_token');
    }

    /**
     * Menampilkan daftar pola
     */
    public function index()
    {
        try {
            $apiUrlBase = env('API_URL');
            if (!$apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }

            if (! $this->token()) {
                return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
            }

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($apiUrlBase.'/pola');

            if ($response->failed()) {
                Log::error('Gagal mengambil data pola', ['body' => $response->body()]);

                return view('pola.index', [
                    'polas' => [],
                    'error' => 'Gagal mengambil data pola dari API',
                ]);
            }

            $polas = $response->json()['data'] ?? [];

            // Ambil data jadwal terpisah jika diperlukan
            foreach ($polas as &$pola) {
                if (isset($pola['jadwal_kode'])) {
                    $jadwalResponse = Http::withToken($this->token())
                        ->acceptJson()
                        ->get($apiUrlBase.'/jadwal/'.$pola['jadwal_kode']);

                    if ($jadwalResponse->successful()) {
                        $pola['jadwal'] = $jadwalResponse->json()['data'] ?? null;
                    }
                }
            }

            return view('pola.index', compact('polas'));
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for pola index.', [
                'exception_message' => $e->getMessage(),
            ]);
            return view('pola.index', ['polas' => [], 'error' => 'Could not connect to the API.']);
        }
    }

    /**
     * Tampilkan form tambah pola
     */
    public function create()
    {
        try {
            $apiUrlBase = env('API_URL');
            if (!$apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }

            // pastikan session token ada
            if (! $this->token()) {
                return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
            }

            $tipes = [];
            $jadwals = [];

            try {
                $respTipe = Http::withToken($this->token())
                    ->acceptJson()
                    ->timeout(10)
                    ->get($apiUrlBase.'/tipe');

                if ($respTipe->successful()) {
                    $tipes = $respTipe->json('data', []);
                }
                else {
                    Log::warning('Gagal ambil tipe dari API', ['status' => $respTipe->status(), 'body' => $respTipe->body()]);
                }
            }
            catch (\Exception $e) {
                Log::error('Exception saat ambil tipe: '.$e->getMessage());
            }

            try {
                $respJadwal = Http::withToken($this->token())
                    ->acceptJson()
                    ->timeout(10)
                    ->get($apiUrlBase.'/jadwal');

                if ($respJadwal->successful()) {
                    $jadwals = $respJadwal->json('data', []);
                }
                else {
                    Log::warning('Gagal ambil jadwal dari API', ['status' => $respJadwal->status(), 'body' => $respJadwal->body()]);
                }
            }
            catch (\Exception $e) {
                Log::error('Exception saat ambil jadwal: '.$e->getMessage());
            }

            // kirim ke view, pastikan selalu ada variabel meskipun kosong
            return view('pola.create', [
                'tipes' => $tipes,
                'jadwals' => $jadwals,
            ]);
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for pola create.', [
                'exception_message' => $e->getMessage(),
            ]);
            return redirect()->route('pola.index')->with('error', 'Could not connect to the API.');
        }
    }

    /**
     * Simpan pola baru
     */
    public function store(Request $request)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (!$apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }

            Log::info('Form Pola diterima:', $request->all());

            if (! $this->token()) {
                return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
            }

            $payload = [
                'tipe_kode' => $request->tipe_kode,
                'jadwal_kode' => $request->jadwal_kode,
                'urut' => $request->urut,
            ];

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post($apiUrlBase.'/pola', $payload);

            Log::info('API Pola Store Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->failed()) {
                $error = $response->json()['message'] ?? 'Gagal menambah pola';

                return back()->withInput()->with('error', $error);
            }

            return redirect()->route('pola.index')->with('success', 'Pola berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for pola store.', [
                'exception_message' => $e->getMessage(),
            ]);
            return back()->withInput()->with('error', 'Could not connect to the API.');
        }
    }

    /**
     * Edit pola
     */
    public function edit($kode)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (!$apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }

            if (! $this->token()) {
                return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
            }

            $pola = Http::withToken($this->token())
                ->acceptJson()
                ->get($apiUrlBase.'/pola/'.$kode);

            $tipe = Http::withToken($this->token())
                ->acceptJson()
                ->get($apiUrlBase.'/tipe');
            $jadwal = Http::withToken($this->token())
                ->acceptJson()
                ->get($apiUrlBase.'/jadwal');

            if ($pola->failed()) {
                return redirect()->route('pola.index')->with('error', 'Data pola tidak ditemukan.');
            }

            return view('pola.edit', [
                'pola' => $pola->json()['data'] ?? null,
                'tipes' => $tipe->json()['data'] ?? [],
                'jadwals' => $jadwal->json()['data'] ?? [],
            ]);
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for pola edit.', [
                'exception_message' => $e->getMessage(),
            ]);
            return redirect()->route('pola.index')->with('error', 'Could not connect to the API.');
        }
    }

    /**
     * Update pola
     */
    public function update(Request $request, $kode)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (!$apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }

            if (! $this->token()) {
                return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
            }

            $payload = [
                'tipe_kode' => $request->tipe_kode,
                'jadwal_kode' => $request->jadwal_kode,
                'urut' => $request->urut,
            ];

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->put($apiUrlBase.'/pola/'.$kode, $payload);

            Log::info('API Pola Update Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->failed()) {
                $error = $response->json()['message'] ?? 'Gagal update pola';

                return back()->withInput()->with('error', $error);
            }

            return redirect()->route('pola.index')->with('success', 'Pola berhasil diperbarui');
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for pola update.', [
                'exception_message' => $e->getMessage(),
            ]);
            return back()->withInput()->with('error', 'Could not connect to the API.');
        }
    }

    /**
     * Hapus pola
     */
    public function destroy($kode)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (!$apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }

            if (! $this->token()) {
                return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
            }

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->delete($apiUrlBase.'/pola/'.$kode);

            if ($response->failed()) {
                $error = $response->json()['message'] ?? 'Gagal menghapus pola';

                return back()->with('error', $error);
            }

            return redirect()->route('pola.index')->with('success', 'Pola berhasil dihapus');
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for pola destroy.', [
                'exception_message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Could not connect to the API.');
        }
    }
}