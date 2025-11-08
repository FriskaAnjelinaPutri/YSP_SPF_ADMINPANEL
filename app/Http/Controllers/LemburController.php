<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LemburController extends Controller
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
     * Display a listing of lembur
     */
    public function index(Request $request)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $periode = $request->get('periode', Carbon::now()->format('Y-m'));
        $status = $request->get('status');
        $karKode = $request->get('kar_kode');

        try {
            // Get list karyawan untuk filter (selalu ambil, bahkan jika request lembur gagal)
            $karyawanResponse = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/karyawan');

            $karyawans = $karyawanResponse->successful() ?
                ($karyawanResponse->json()['data'] ?? []) : [];

            $url = $this->apiBase() . '/lembur';
            $params = ['periode' => $periode];

            if ($status) {
                $params['status'] = $status;
            }
            if ($karKode) {
                $params['kar_kode'] = $karKode;
            }

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($url, $params);

            if ($response->failed()) {
                Log::error('Gagal mengambil data lembur', ['body' => $response->body()]);
                return view('lembur.index', [
                    'lemburs' => [],
                    'summary' => [],
                    'karyawans' => $karyawans,
                    'periode' => $periode,
                    'status' => $status,
                    'error' => 'Gagal mengambil data lembur dari API',
                ]);
            }

            $data = $response->json();
            $lemburs = $data['data'] ?? [];
            $summary = $data['summary'] ?? [];

            return view('lembur.index', compact('lemburs', 'periode', 'summary', 'karyawans', 'status'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data lembur', ['error' => $e->getMessage()]);
            return view('lembur.index', [
                'lemburs' => [],
                'summary' => [],
                'karyawans' => [], // In case of total failure, pass empty array
                'periode' => $periode,
                'status' => $status,
                'error' => 'Terjadi kesalahan saat mengambil data',
            ]);
        }
    }

    /**
     * Show the form for creating a new lembur
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

            return view('lembur.create', compact('karyawans'));

        } catch (\Exception $e) {
            Log::error('Error saat load form create lembur', ['error' => $e->getMessage()]);
            return redirect()->route('lembur.index')
                ->with('error', 'Terjadi kesalahan saat memuat form');
        }
    }

    /**
     * Store a newly created lembur
     */
    public function store(Request $request)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'kar_kode' => 'nullable|string|max:50',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'alasan' => 'required|string|max:500',
            'status' => 'required|in:Pending,Approved,Rejected',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post($this->apiBase() . '/lembur', [
                    'kar_kode' => $request->kar_kode,
                    'tanggal' => $request->tanggal,
                    'jam_mulai' => $request->jam_mulai . ':00',
                    'jam_selesai' => $request->jam_selesai . ':00',
                    'alasan' => $request->alasan,
                    'status' => $request->status,
                    'keterangan' => $request->keterangan,
                ]);

            if ($response->successful()) {
                return redirect()->route('lembur.index')
                    ->with('success', 'Data lembur berhasil ditambahkan');
            }

            $error = $response->json()['message'] ?? 'Gagal menyimpan data lembur';
            return back()->withInput()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan lembur', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Display the specified lembur
     */
    public function show($lembur)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }
        Log::info($lembur);
        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/lembur/' . $lembur);

            if ($response->successful()) {
                $lembur = $response->json()['data'] ?? null;
                return view('lembur.show', compact('lembur'));
            }

            return redirect()->route('lembur.index')
                ->with('error', 'Data lembur tidak ditemukan');

        } catch (\Exception $e) {
            Log::error('Error saat mengambil detail lembur', ['error' => $e->getMessage()]);
            return redirect()->route('lembur.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Show the form for editing the specified lembur
     */
    public function edit($lembur)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/lembur/' . $lembur);

            if ($response->failed()) {
                return redirect()->route('lembur.index')
                    ->with('error', 'Data lembur tidak ditemukan');
            }

            $lembur = $response->json()['data'] ?? null;

            // Get list karyawan
            $karyawanResponse = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/karyawan');

            $karyawans = $karyawanResponse->successful() ?
                ($karyawanResponse->json()['data'] ?? []) : [];

            return view('lembur.edit', compact('lembur', 'karyawans'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data lembur untuk edit', ['error' => $e->getMessage()]);
            return redirect()->route('lembur.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Update the specified lembur
     */
    public function update(Request $request, $lembur)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'kar_kode' => 'required|',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'alasan' => 'required|string|max:500',
            'status' => 'required|in:Pending,Approved,Rejected',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->put($this->apiBase() . '/lembur/' . $lembur, [
                    'kar_kode' => $request->kar_kode,
                    'tanggal' => $request->tanggal,
                    'jam_mulai' => $request->jam_mulai . ':00',
                    'jam_selesai' => $request->jam_selesai . ':00',
                    'alasan' => $request->alasan,
                    'status' => $request->status,
                    'keterangan' => $request->keterangan,
                ]);

            if ($response->successful()) {
                return redirect()->route('lembur.index')
                    ->with('success', 'Data lembur berhasil diperbarui');
            }

            $error = $response->json()['message'] ?? 'Gagal memperbarui data lembur';
            return back()->withInput()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat update lembur', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    /**
     * Remove the specified lembur
     */
    public function destroy($lembur)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->delete($this->apiBase() . '/lembur/' . $lembur);

            if ($response->successful()) {
                return redirect()->route('lembur.index')
                    ->with('success', 'Data lembur berhasil dihapus');
            }

            $error = $response->json()['message'] ?? 'Gagal menghapus data lembur';
            return back()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat menghapus lembur', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Approve lembur
     */
    public function approve($id)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post($this->apiBase() . '/lembur/approve', [
                    'id' => $id,
                    'status' => 'Approved'
                ]);

            if ($response->successful()) {
                return redirect()->route('lembur.index')
                    ->with('success', 'Lembur berhasil disetujui');
            }

            $error = $response->json()['message'] ?? 'Gagal menyetujui lembur';
            return back()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat approve lembur', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat memproses approval');
        }
    }

    /**
     * Reject lembur
     */
    public function reject(Request $request, $id)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post($this->apiBase() . '/lembur/approve', [
                    'id' => $id,
                    'status' => 'Rejected',
                    'keterangan' => $request->keterangan
                ]);

            if ($response->successful()) {
                return redirect()->route('lembur.index')
                    ->with('success', 'Lembur berhasil ditolak');
            }

            $error = $response->json()['message'] ?? 'Gagal menolak lembur';
            return back()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat reject lembur', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat memproses penolakan');
        }
    }
}
