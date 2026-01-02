<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Sesi login habis. Silakan login ulang.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/jadwal');

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
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        return view('jadwal.create');
    }

    /**
     * Simpan data jadwal baru ke API
     */
    public function store(Request $request)
    {
        if (! $this->token()) {
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
                ->post($this->apiBase().'/jadwal', $payload);

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

            $errorMsg = $response->json()['message'] ?? 'Gagal menambah jadwal. Response: '.$response->body();

            return back()->withInput()->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan jadwal', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage());
        }
    }

    /**
     * Edit jadwal
     */
    public function edit($kode)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/jadwal/'.$kode);

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
        if (! $this->token()) {
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
                'payload' => $payload,
            ]);

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->timeout(30)
                ->put($this->apiBase().'/jadwal/'.$kode, $payload);

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

            $errorMsg = $response->json()['message'] ?? 'Gagal memperbarui jadwal. Response: '.$response->body();

            return back()->withInput()->with('error', $errorMsg);

        } catch (\Exception $e) {
            Log::error('Error saat update jadwal', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: '.$e->getMessage());
        }
    }

    /**
     * Hapus jadwal
     */
    public function destroy($kode)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->delete($this->apiBase().'/jadwal/'.$kode);

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

    public function generate()
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        return view('jadwal.generate');
    }

    public function generateStore(Request $request)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:'.(date('Y') - 5),
        ]);

        $bulan = (int) $request->input('bulan');
        $tahun = (int) $request->input('tahun');

        try {
            $karyawanResponse = Http::withToken($this->token())->get($this->apiBase().'/karyawan');
            if ($karyawanResponse->failed()) {
                return back()->with('error', 'Gagal mengambil data karyawan.');
            }
            $karyawans = $karyawanResponse->json()['data'] ?? [];

            $polaResponse = Http::withToken($this->token())->get($this->apiBase().'/pola');
            if ($polaResponse->failed()) {
                return back()->with('error', 'Gagal mengambil data pola kerja.');
            }
            $polas = $polaResponse->json()['data'] ?? [];

            $polaByTipe = collect($polas)->sortBy('urut')->groupBy('tipe_kode');

            $jadwalKerja = [];
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

            foreach ($karyawans as $karyawan) {
                $tipeKode = $karyawan['tipe_kode'] ?? null;
                if (! $tipeKode || ! isset($polaByTipe[$tipeKode])) {
                    continue;
                }

                $polaKaryawan = $polaByTipe[$tipeKode];
                $polaCount = $polaKaryawan->count();
                if ($polaCount === 0) {
                    continue;
                }

                for ($hari = 1; $hari <= $daysInMonth; $hari++) {
                    $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
                    $polaIndex = ($hari - 1) % $polaCount;
                    $jadwalKode = $polaKaryawan[$polaIndex]['jadwal_kode'];

                    $jadwalKerja[] = [
                        'kar_kode' => $karyawan['kar_kode'],
                        'jadwal_kode' => $jadwalKode,
                        'tanggal' => $tanggal,
                    ];
                }
            }

            if (empty($jadwalKerja)) {
                return back()->with('error', 'Tidak ada jadwal yang bisa di-generate.');
            }

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->timeout(120) // Tambahkan timeout 120 detik
                ->post($this->apiBase().'/jadwal-kerja/generate', [
                    'jadwal_kerja' => $jadwalKerja,
                ]);

            if ($response->failed()) {
                $msg = $response->json()['message'] ?? 'Gagal menyimpan jadwal ke API.';
                Log::error('Generate jadwal gagal', ['response' => $response->body()]);

                return back()->with('error', $msg);
            }

            return redirect()
                ->route('jadwal.hasil', ['bulan' => $bulan, 'tahun' => $tahun])
                ->with('success', 'Jadwal kerja berhasil digenerate!');
        } catch (\Exception $e) {
            Log::error('Error generate jadwal', ['exception' => $e]);

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function hasilGenerate(Request $request)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        // PASTIKAN integer!
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/jadwal-kerja', [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'page' => $request->input('page', 1),
                    'per_page' => 30,
                ]);

            if ($response->failed()) {
                Log::error('API jadwal-kerja gagal', ['status' => $response->status(), 'body' => $response->body()]);

                return view('jadwal.hasil', [
                    'paginator' => null,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'error' => 'Gagal mengambil data jadwal.',
                ]);
            }

            $apiData = $response->json()['data'] ?? [];

            $jadwalKerja = $apiData['data'] ?? [];

            $paginator = new LengthAwarePaginator(
                $jadwalKerja,
                $apiData['total'] ?? 0,
                $apiData['per_page'] ?? 30,
                $apiData['current_page'] ?? 1,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('jadwal.hasil', compact('paginator', 'bulan', 'tahun'));
        } catch (\Exception $e) {
            Log::error('Error hasil jadwal', ['exception' => $e]);

            return view('jadwal.hasil', [
                'paginator' => null,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'error' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }
}
