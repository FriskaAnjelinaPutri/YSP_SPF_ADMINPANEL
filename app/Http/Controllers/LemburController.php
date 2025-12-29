<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LemburController extends Controller
{
    private function apiBase()
    {
        return rtrim(env('API_URL'), '/');
    }

    private function token()
    {
        return Session::get('api_token');
    }

    /** =========================
     *  INDEX (LIST DATA)
     *  ========================= */
    public function index(Request $request)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $periode = $request->input('periode', Carbon::now()->format('Y-m'));
        $status = $request->input('status');
        $karKode = $request->input('kar_kode');

        try {
            // Ambil list karyawan
            $karyawanRes = Http::withToken($this->token())
                ->acceptJson()
                ->get("{$this->apiBase()}/karyawan");

            $karyawans = $karyawanRes->successful() ? ($karyawanRes->json()['data'] ?? []) : [];

            // Ambil data lembur
            $params = ['periode' => $periode];
            if ($status) {
                $params['status'] = $status;
            }
            if ($karKode) {
                $params['kar_kode'] = $karKode;
            }

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get("{$this->apiBase()}/lembur", $params);

            if ($response->failed()) {
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

            return view('lembur.index', [
                'lemburs' => $data['data'] ?? [],
                'summary' => $data['summary'] ?? [],
                'karyawans' => $karyawans,
                'periode' => $periode,
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            Log::error('Error lembur index: '.$e->getMessage());

            return view('lembur.index', [
                'lemburs' => [],
                'summary' => [],
                'karyawans' => [],
                'periode' => $periode,
                'status' => $status,
                'error' => 'Terjadi kesalahan saat mengambil data',
            ]);
        }
    }

    /** =========================
     *  CREATE FORM
     *  ========================= */
    public function create()
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $res = Http::withToken($this->token())
                ->acceptJson()
                ->get("{$this->apiBase()}/karyawan");

            $karyawans = $res->successful() ? ($res->json()['data'] ?? []) : [];

            return view('lembur.create', compact('karyawans'));
        } catch (\Exception $e) {
            Log::error('Error create lembur: '.$e->getMessage());

            return redirect()->route('lembur.index')->with('error', 'Gagal memuat form lembur.');
        }
    }

    /** =========================
     *  STORE (CREATE)
     *  ========================= */
    public function store(Request $request)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token tidak ditemukan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'alasan' => 'required|string|max:500',
            'status' => 'required|in:Pending,Approved,Rejected',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            $payload = [
                'tanggal' => $validated['tanggal'],
                'jam_mulai' => $validated['jam_mulai'].':00',
                'jam_selesai' => $validated['jam_selesai'].':00',
                'alasan' => $validated['alasan'],
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post("{$this->apiBase()}/lembur", $payload);

            if ($response->successful()) {
                return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil ditambahkan.');
            }

            $msg = $response->json()['message'] ?? 'Gagal menyimpan data.';

            return back()->withInput()->with('error', $msg);
        } catch (\Exception $e) {
            Log::error('Error store lembur: '.$e->getMessage());

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /** =========================
     *  EDIT FORM
     *  ========================= */
    public function edit($id)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token tidak ditemukan.');
        }

        try {
            $lemburRes = Http::withToken($this->token())
                ->acceptJson()
                ->get("{$this->apiBase()}/lembur/{$id}");

            if ($lemburRes->failed()) {
                return redirect()->route('lembur.index')->with('error', 'Data lembur tidak ditemukan.');
            }

            $lembur = $lemburRes->json()['data']['lembur'] ?? null;
            if (! $lembur) {
                return redirect()->route('lembur.index')->with('error', 'Data lembur kosong.');
            }

            $karyawanRes = Http::withToken($this->token())
                ->acceptJson()
                ->get("{$this->apiBase()}/karyawan");

            $karyawans = $karyawanRes->successful() ? ($karyawanRes->json()['data'] ?? []) : [];

            return view('lembur.edit', compact('lembur', 'karyawans'));
        } catch (\Exception $e) {
            Log::error('Error edit lembur: '.$e->getMessage());

            return redirect()->route('lembur.index')->with('error', 'Terjadi kesalahan.');
        }
    }

    /** =========================
     *  UPDATE
     *  ========================= */
    public function update(Request $request, $id)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token tidak ditemukan.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'alasan' => 'required|string|max:500',
            'status' => 'required|in:Pending,Approved,Rejected',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            $payload = [
                'tanggal' => $validated['tanggal'],
                'jam_mulai' => $validated['jam_mulai'].':00',
                'jam_selesai' => $validated['jam_selesai'].':00',
                'alasan' => $validated['alasan'],
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->put("{$this->apiBase()}/lembur/{$id}", $payload);

            if ($response->successful()) {
                return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil diperbarui.');
            }

            $msg = $response->json()['message'] ?? 'Gagal memperbarui data.';

            return back()->withInput()->with('error', $msg);
        } catch (\Exception $e) {
            Log::error('Error update lembur: '.$e->getMessage());

            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /** =========================
     *  DELETE
     *  ========================= */
    public function destroy($id)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token tidak ditemukan.');
        }

        try {
            $res = Http::withToken($this->token())
                ->acceptJson()
                ->delete("{$this->apiBase()}/lembur/{$id}");

            if ($res->successful()) {
                return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil dihapus.');
            }

            $msg = $res->json()['message'] ?? 'Gagal menghapus data.';

            return back()->with('error', $msg);
        } catch (\Exception $e) {
            Log::error('Error delete lembur: '.$e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    /** =========================
     *  APPROVE / REJECT
     *  ========================= */
    public function approve($id)
    {
        return $this->processApproval($id, 'Approved');
    }

    public function reject(Request $request, $id)
    {
        return $this->processApproval($id, 'Rejected', $request->input('keterangan'));
    }

    private function processApproval($id, $status, $keterangan = null)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token tidak ditemukan.');
        }

        try {
            $payload = ['id' => $id, 'status' => $status];
            if ($keterangan) {
                $payload['keterangan'] = $keterangan;
            }

            $res = Http::withToken($this->token())
                ->acceptJson()
                ->post("{$this->apiBase()}/lembur/approve", $payload);

            if ($res->successful()) {
                $msg = $status === 'Approved' ? 'Lembur berhasil disetujui.' : 'Lembur berhasil ditolak.';

                return redirect()->route('lembur.index')->with('success', $msg);
            }

            $msg = $res->json()['message'] ?? 'Gagal memproses permintaan.';

            return back()->with('error', $msg);
        } catch (\Exception $e) {
            Log::error("Error {$status} lembur: ".$e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat memproses.');
        }
    }

    public function show($id)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token tidak ditemukan.');
        }

        try {
            $res = Http::withToken($this->token())
                ->acceptJson()
                ->get("{$this->apiBase()}/lembur/{$id}");

            if ($res->status() === 403) {
                return redirect()->route('lembur.index')
                    ->with('error', 'Anda tidak memiliki akses (Admin only).');
            }

            if ($res->status() === 401) {
                return redirect()->route('login')
                    ->with('error', 'Sesi habis, silakan login ulang.');
            }

            if ($res->failed()) {
                return redirect()->route('lembur.index')
                    ->with('error', $res->json()['message'] ?? 'Data lembur tidak ditemukan.');
            }

            $data = $res->json()['data'] ?? null;

            if (! $data || ! isset($data['lembur'])) {
                return redirect()->route('lembur.index')
                    ->with('error', 'Data lembur kosong.');
            }

            $lembur = $data['lembur'];
            $durasi_jam = $data['durasi_jam'] ?? null;
            $durasi_text = $data['durasi_text'] ?? null;

            return view('lembur.show', compact('lembur', 'durasi_jam', 'durasi_text'));

        } catch (\Exception $e) {
            Log::error('Error show lembur: ' . $e->getMessage());
            return redirect()->route('lembur.index')
                ->with('error', 'Terjadi kesalahan.');
        }
    }

}
