<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AbsensiController extends Controller
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
     * Display a listing of absensi
     */
    public function index(Request $request)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            // Ambil parameter dari request
            $periode = $request->get('periode', Carbon::now()->format('Y-m'));
            $kar_nama = $request->get('kar_nama');
            $status = $request->get('status');
            $page = $request->get('page', 1); // Ambil halaman saat ini

            $url = $this->apiBase().'/absensi';

            // Siapkan parameter untuk API
            $params = [
                'periode' => $periode,
                'page' => $page, // Tambahkan parameter halaman
            ];
            if ($kar_nama) {
                $params['kar_nama'] = $kar_nama;
            }
            if ($status) {
                $params['status'] = $status;
            }

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($url, $params);

            if ($response->failed()) {
                Log::error('Gagal mengambil data absensi', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return view('absensi.index', [
                    'absensis' => [], 'links' => null, 'meta' => null,
                    'periode' => $periode, 'summary' => [], 'kar_nama' => $kar_nama,
                    'error' => 'Gagal mengambil data absensi dari API',
                ]);
            }

            $data = $response->json();
            $absensis = $data['data'] ?? [];
            $links = $data['links'] ?? null; // API does not provide pagination links
            $meta = $data['meta'] ?? null;   // API does not provide pagination meta
            $summary = $data['summary'] ?? [];

            Log::info('Pagination data from API:', ['links' => $links, 'meta' => $meta]);

            return view('absensi.index', compact('absensis', 'links', 'meta', 'periode', 'summary', 'kar_nama', 'status'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data absensi', ['error' => $e->getMessage()]);

            return view('absensi.index', [
                'absensis' => [], 'links' => null, 'meta' => null,
                'periode' => Carbon::now()->format('Y-m'),
                'summary' => [], 'kar_nama' => null,
                'error' => 'Terjadi kesalahan saat mengambil data: '.$e->getMessage(),
            ]);
        }
    }
    // /**
    //  * Show the form for creating a new absensi
    //  */
    // public function create()
    // {
    //     if (!$this->token()) {
    //         return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
    //     }

    //     try {
    //         // Get list karyawan
    //         $response = Http::withToken($this->token())
    //             ->acceptJson()
    //             ->get($this->apiBase() . '/karyawan');

    //         $karyawans = $response->successful() ?
    //             ($response->json()['data'] ?? []) : [];

    //         return view('absensi.create', compact('karyawans'));

    //     } catch (\Exception $e) {
    //         Log::error('Error saat load form create absensi', ['error' => $e->getMessage()]);
    //         return redirect()->route('absensi.index')
    //             ->with('error', 'Terjadi kesalahan saat memuat form');
    //     }
    // }

    // /**
    //  * Store a newly created absensi
    //  */
    // public function store(Request $request)
    // {
    //     if (!$this->token()) {
    //         return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
    //     }

    //     $request->validate([
    //         'kar_kode' => 'required|integer',
    //         'tanggal' => 'required|date',
    //         'check_in' => 'required|date_format:H:i',
    //         'check_out' => 'nullable|date_format:H:i',
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'status' => 'required|in:Hadir,Terlambat,Izin,Sakit,Alpha',
    //         'keterangan' => 'nullable|string|max:500'
    //     ], [
    //         'kar_kode.required' => 'Karyawan wajib dipilih',
    //         'tanggal.required' => 'Tanggal wajib diisi',
    //         'check_in.required' => 'Jam check-in wajib diisi',
    //         'latitude.required' => 'Latitude wajib diisi',
    //         'longitude.required' => 'Longitude wajib diisi',
    //         'status.required' => 'Status wajib dipilih',
    //     ]);

    //     try {
    //         $payload = [
    //             'kar_kode' => $request->kar_kode,
    //             'tanggal' => $request->tanggal,
    //             'check_in' => $request->check_in . ':00',
    //             'check_out' => $request->check_out ? $request->check_out . ':00' : null,
    //             'latitude' => $request->latitude,
    //             'longitude' => $request->longitude,
    //             'status' => $request->status,
    //             'keterangan' => $request->keterangan,
    //         ];

    //         Log::info('Absensi Store Payload:', $payload);

    //         $response = Http::withToken($this->token())
    //             ->acceptJson()
    //             ->timeout(30)
    //             ->post($this->apiBase() . '/absensi', $payload);

    //         Log::info('Absensi Store Response:', [
    //             'status' => $response->status(),
    //             'body' => $response->body(),
    //         ]);

    //         if ($response->successful()) {
    //             return redirect()->route('absensi.index')
    //                 ->with('success', 'Data absensi berhasil ditambahkan');
    //         }

    //         $error = $response->json()['message'] ?? 'Gagal menyimpan data absensi';
    //         return back()->withInput()->with('error', $error);

    //     } catch (\Exception $e) {
    //         Log::error('Error saat menyimpan absensi', ['error' => $e->getMessage()]);
    //         return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    //     }
    // }

    /**
     * Display the specified absensi
     */
    public function show($id)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/absensi/'.$id);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('API response for show absensi:', $responseData);

                // Check multiple possible structures for the data
                $absensi = data_get($responseData, 'data.absensi', data_get($responseData, 'data'));
                $durasi = data_get($responseData, 'data.durasi_kerja');

                // If API returns a list for a single item, take the first one.
                if (is_array($absensi) && isset($absensi[0])) {
                    $absensi = $absensi[0];
                } elseif (is_array($absensi) && empty($absensi)) {
                    $absensi = null;
                }

                if ($absensi) {
                    // Get list karyawan to embed into absensi
                    $karyawanResponse = Http::withToken($this->token())
                        ->acceptJson()
                        ->get($this->apiBase().'/karyawan');

                    $karyawans = $karyawanResponse->successful() ?
                        ($karyawanResponse->json()['data'] ?? []) : [];

                    // Embed karyawan data into absensi if kar_kode exists
                    if (isset($absensi['kar_kode']) && ! empty($karyawans)) {
                        $foundKaryawan = collect($karyawans)->firstWhere('kar_kode', $absensi['kar_kode']);
                        if ($foundKaryawan) {
                            $absensi['karyawan'] = $foundKaryawan;
                        } else {
                            Log::warning('Karyawan with kar_kode not found for absensi.', ['kar_kode' => $absensi['kar_kode'], 'absensi_id' => $id]);
                            $absensi['karyawan'] = ['kar_nama' => 'N/A']; // Fallback
                        }
                    } else {
                        $absensi['karyawan'] = ['kar_nama' => 'N/A']; // Fallback if no kar_kode or no karyawan data
                    }

                    return view('absensi.show', compact('absensi', 'durasi'));
                }

                Log::warning('Could not find "absensi" data in API response for show.', ['id' => $id, 'response' => $responseData]);

                return redirect()->route('absensi.index')->with('error', 'Struktur data absensi dari API tidak valid.');
            }

            return redirect()->route('absensi.index')
                ->with('error', 'Data absensi tidak ditemukan');

        } catch (\Exception $e) {
            Log::error('Error saat mengambil detail absensi', ['error' => $e->getMessage()]);

            return redirect()->route('absensi.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Show the form for editing the specified absensi
     */
    public function edit($id)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/absensi/'.$id); // Corrected endpoint for edit

            if ($response->failed()) {
                Log::error('API call failed for edit absensi', ['id' => $id, 'status' => $response->status()]);

                return redirect()->route('absensi.index')
                    ->with('error', 'Data absensi tidak ditemukan');
            }

            $responseData = $response->json(); // Get raw response
            Log::info('API response for edit absensi:', $responseData); // Log it

            // Check multiple possible structures for the data
            $absensi = data_get($responseData, 'data.absensi', data_get($responseData, 'data'));

            // If API returns a list for a single item, take the first one.
            if (is_array($absensi) && isset($absensi[0])) {
                $absensi = $absensi[0];
            } elseif (is_array($absensi) && empty($absensi)) {
                $absensi = null;
            }

            if (! $absensi) {
                Log::warning('Could not find "absensi" data in API response for edit.', ['id' => $id, 'response' => $responseData]);

                return redirect()->route('absensi.index')->with('error', 'Struktur data absensi dari API tidak valid untuk edit.');
            }

            // Get list karyawan
            $karyawanResponse = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/karyawan');

            $karyawans = $karyawanResponse->successful() ?
                ($karyawanResponse->json()['data'] ?? []) : [];

            // Get list jadwal
            $jadwalResponse = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/jadwal');

            $jadwals = $jadwalResponse->successful() ?
                ($jadwalResponse->json()['data'] ?? []) : [];

            // Embed karyawan data into absensi if kar_kode exists
            if (isset($absensi['kar_kode']) && ! empty($karyawans)) {
                $foundKaryawan = collect($karyawans)->firstWhere('kar_kode', $absensi['kar_kode']);
                if ($foundKaryawan) {
                    $absensi['karyawan'] = $foundKaryawan;
                } else {
                    Log::warning('Karyawan with kar_kode not found for absensi.', ['kar_kode' => $absensi['kar_kode'], 'absensi_id' => $id]);
                    $absensi['karyawan'] = ['kar_nama' => 'N/A']; // Fallback
                }
            } else {
                $absensi['karyawan'] = ['kar_nama' => 'N/A']; // Fallback if no kar_kode or no karyawan data
            }

            return view('absensi.edit', compact('absensi', 'karyawans', 'jadwals'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data absensi untuk edit', ['error' => $e->getMessage()]);

            return redirect()->route('absensi.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Update the specified absensi
     */
    public function update(Request $request, $id)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|in:Hadir,Terlambat,Izin,Sakit,Alpha',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'latitude.required' => 'Latitude wajib diisi',
            'longitude.required' => 'Longitude wajib diisi',
            'status.required' => 'Status wajib dipilih',
        ]);

        try {
            $payload = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ];

            Log::info('Absensi Update Payload:', [
                'id' => $id,
                'payload' => $payload,
            ]);

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->timeout(30)
                ->put($this->apiBase().'/absensi/'.$id, $payload);

            Log::info('Absensi Update Response:', [
                'id' => $id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                return redirect()->route('absensi.index')
                    ->with('success', 'Data absensi berhasil diperbarui');
            }

            $error = $response->json()['message'] ?? 'Gagal memperbarui data absensi';

            return back()->withInput()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat update absensi', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified absensi
     */
    public function destroy($id)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->delete($this->apiBase().'/absensi/'.$id);

            Log::info('Absensi Delete Response:', [
                'id' => $id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                return redirect()->route('absensi.index')
                    ->with('success', 'Data absensi berhasil dihapus');
            }

            $error = $response->json()['message'] ?? 'Gagal menghapus data absensi';

            return back()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat menghapus absensi', ['error' => $e->getMessage()]);

            return back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Export absensi to Excel
     */
    public function export(Request $request)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $periode = $request->get('periode', Carbon::now()->format('Y-m'));

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->timeout(60)
                ->get($this->apiBase().'/absensi/export', [
                    'periode' => $periode,
                ]);

            if ($response->successful()) {
                $filename = 'absensi_'.$periode.'.xlsx';

                // Jika API mengembalikan file Excel
                if ($response->header('Content-Type') === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    return response($response->body())
                        ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                        ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
                }

                // Jika API mengembalikan JSON (untuk sementara)
                $data = $response->json();

                return redirect()->route('absensi.index')
                    ->with('success', 'Export berhasil. Total data: '.count($data['data'] ?? []));
            }

            return back()->with('error', 'Gagal mengexport data absensi');

        } catch (\Exception $e) {
            Log::error('Error saat export absensi', ['error' => $e->getMessage()]);

            return back()->with('error', 'Terjadi kesalahan saat export data');
        }
    }

    /**
     * Get absensi data for AJAX request (for charts/statistics)
     */
    public function getStatistics(Request $request)
    {
        if (! $this->token()) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan',
            ], 401);
        }

        try {
            $periode = $request->get('periode', Carbon::now()->format('Y-m'));

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/absensi', [
                    'periode' => $periode,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'summary' => $data['summary'] ?? [],
                    'data' => $data['data'] ?? [],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data statistik',
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error get statistics', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
            ], 500);
        }
    }
}
