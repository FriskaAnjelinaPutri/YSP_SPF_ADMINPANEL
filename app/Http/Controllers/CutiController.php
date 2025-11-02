<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CutiController extends Controller
{
    private function apiBase()
    {
        return env('API_URL');
    }

    private function token()
    {
        return Session::get('api_token');
    }

    /**
     * Display a listing of cuti
     */
    public function index(Request $request)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $periode = $request->get('periode', Carbon::now()->format('Y-m'));
            $status = $request->get('status');
            $karyawanId = $request->get('karyawan_id');

            $url = $this->apiBase() . '/admin/cuti';
            $params = ['periode' => $periode];

            if ($status) {
                $params['status'] = $status;
            }
            if ($karyawanId) {
                $params['karyawan_id'] = $karyawanId;
            }

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($url, $params);

            if ($response->failed()) {
                Log::error('Gagal mengambil data cuti', ['body' => $response->body()]);
                return view('cuti.index', [
                    'cutis' => [],
                    'periode' => $periode,
                    'error' => 'Gagal mengambil data cuti dari API',
                ]);
            }

            $data = $response->json();
            $cutis = $data['data'] ?? [];
            $summary = $data['summary'] ?? [];

            // Get list karyawan untuk filter
            $karyawanResponse = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/karyawan');

            $karyawans = $karyawanResponse->successful() ?
                ($karyawanResponse->json()['data'] ?? []) : [];

            return view('cuti.index', compact('cutis', 'periode', 'summary', 'karyawans', 'status'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data cuti', ['error' => $e->getMessage()]);
            return view('cuti.index', [
                'cutis' => [],
                'periode' => Carbon::now()->format('Y-m'),
                'error' => 'Terjadi kesalahan saat mengambil data',
            ]);
        }
    }

    /**
     * Show the form for creating a new cuti
     */
    public function create()
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            // Get list karyawan
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/karyawan');

            $karyawans = $response->successful() ?
                ($response->json()['data'] ?? []) : [];

            return view('cuti.create', compact('karyawans'));

        } catch (\Exception $e) {
            Log::error('Error saat load form create cuti', ['error' => $e->getMessage()]);
            return redirect()->route('cuti.index')
                ->with('error', 'Terjadi kesalahan saat memuat form');
        }
    }

    /**
     * Store a newly created cuti
     */
    public function store(Request $request)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'karyawan_id' => 'required|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_cuti' => 'required|in:Tahunan,Sakit,Melahirkan,Menikah,Keluarga Meninggal,Lainnya',
            'alasan' => 'required|string|max:500',
            'status' => 'required|in:Pending,Approved,Rejected',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post($this->apiBase() . '/admin/cuti', $request->all());

            if ($response->successful()) {
                return redirect()->route('cuti.index')
                    ->with('success', 'Data cuti berhasil ditambahkan');
            }

            $error = $response->json()['message'] ?? 'Gagal menyimpan data cuti';
            return back()->withInput()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan cuti', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Display the specified cuti
     */
    public function show($id)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/admin/cuti/' . $id);

            if ($response->successful()) {
                $cuti = $response->json()['data'] ?? null;
                return view('cuti.show', compact('cuti'));
            }

            return redirect()->route('cuti.index')
                ->with('error', 'Data cuti tidak ditemukan');

        } catch (\Exception $e) {
            Log::error('Error saat mengambil detail cuti', ['error' => $e->getMessage()]);
            return redirect()->route('cuti.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Show the form for editing the specified cuti
     */
    public function edit($id)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/admin/cuti/' . $id);

            if ($response->failed()) {
                return redirect()->route('cuti.index')
                    ->with('error', 'Data cuti tidak ditemukan');
            }

            $cuti = $response->json()['data'] ?? null;

            // Get list karyawan
            $karyawanResponse = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/karyawan');

            $karyawans = $karyawanResponse->successful() ?
                ($karyawanResponse->json()['data'] ?? []) : [];

            return view('cuti.edit', compact('cuti', 'karyawans'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data cuti untuk edit', ['error' => $e->getMessage()]);
            return redirect()->route('cuti.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Update the specified cuti
     */
    public function update(Request $request, $id)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_cuti' => 'required|in:Tahunan,Sakit,Melahirkan,Menikah,Keluarga Meninggal,Lainnya',
            'alasan' => 'required|string|max:500',
            'status' => 'required|in:Pending,Approved,Rejected',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->put($this->apiBase() . '/admin/cuti/' . $id, [
                    'tanggal_mulai' => $request->tanggal_mulai,
                    'tanggal_selesai' => $request->tanggal_selesai,
                    'jenis_cuti' => $request->jenis_cuti,
                    'alasan' => $request->alasan,
                    'status' => $request->status,
                    'keterangan' => $request->keterangan,
                ]);

            if ($response->successful()) {
                return redirect()->route('cuti.index')
                    ->with('success', 'Data cuti berhasil diperbarui');
            }

            $error = $response->json()['message'] ?? 'Gagal memperbarui data cuti';
            return back()->withInput()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat update cuti', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    /**
     * Remove the specified cuti
     */
    public function destroy($id)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->delete($this->apiBase() . '/admin/cuti/' . $id);

            if ($response->successful()) {
                return redirect()->route('cuti.index')
                    ->with('success', 'Data cuti berhasil dihapus');
            }

            $error = $response->json()['message'] ?? 'Gagal menghapus data cuti';
            return back()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat menghapus cuti', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Approve cuti
     */
    public function approve($id)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post($this->apiBase() . '/admin/cuti/approve', [
                    'id' => $id,
                    'status' => 'Approved'
                ]);

            if ($response->successful()) {
                return redirect()->route('cuti.index')
                    ->with('success', 'Cuti berhasil disetujui');
            }

            $error = $response->json()['message'] ?? 'Gagal menyetujui cuti';
            return back()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat approve cuti', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat memproses approval');
        }
    }

    /**
     * Reject cuti
     */
    public function reject(Request $request, $id)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post($this->apiBase() . '/admin/cuti/approve', [
                    'id' => $id,
                    'status' => 'Rejected',
                    'keterangan' => $request->keterangan
                ]);

            if ($response->successful()) {
                return redirect()->route('cuti.index')
                    ->with('success', 'Cuti berhasil ditolak');
            }

            $error = $response->json()['message'] ?? 'Gagal menolak cuti';
            return back()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat reject cuti', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat memproses penolakan');
        }
    }
}
