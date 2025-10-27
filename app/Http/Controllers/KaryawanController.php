<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api';

    /**
     * Ambil token dari session
     */
    private function token()
    {
        return session('api_token');
    }

    /**
     * Tampilkan daftar karyawan
     */
    public function index(Request $request)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $search = $request->get('search');
        $params = $search ? ['search' => $search] : [];

        $response = Http::withToken($this->token())->get($this->apiBase . '/karyawan', $params);

        if ($response->failed()) {
            $status = $response->status();
            $errorMsg = $response->json()['message'] ?? "Gagal mengakses API. Status: {$status}";
            Log::error('API Karyawan Index Error: ' . $errorMsg);

            if ($status === 401) {
                session()->forget('api_token');
                return redirect()->route('login')->with('error', 'Token tidak valid atau expired.');
            }

            return view('karyawan.index', [
                'karyawans' => [],
                'error' => $errorMsg,
            ]);
        }

        $data = $response->json()['data'] ?? [];

        $karyawans = collect($data)->map(function ($item) {
            $obj = (object) $item;

            // Mapping untuk jabatan
            $obj->jabatan = isset($item['jabatan']) ? (object) ['jabatan_nama' => $item['jabatan']['jabatan_nama'] ?? '-'] : (object) ['jabatan_nama' => $item['jabatan_nama'] ?? '-'];

            // Mapping untuk unit
            $obj->unit = isset($item['unit']) ? (object) ['unit_nama' => $item['unit']['unit_nama'] ?? '-'] : (object) ['unit_nama' => $item['unit_nama'] ?? '-'];

            // Mapping untuk golongan
            $obj->golongan = isset($item['golongan']) ? (object) ['golongan_nama' => $item['golongan']['golongan_nama'] ?? '-'] : (object) ['golongan_nama' => $item['golongan_nama'] ?? '-'];

            // ✅ Tambahkan mapping untuk tipe
            $obj->tipe = isset($item['tipe']) ? (object) ['tipe_nama' => $item['tipe']['tipe_nama'] ?? '-'] : (object) ['tipe_nama' => $item['tipe_nama'] ?? '-'];

            // ✅ Tambahkan mapping untuk profesi
            $obj->profesi = isset($item['profesi']) ? (object) ['profesi_nama' => $item['profesi']['profesi_nama'] ?? '-'] : (object) ['profesi_nama' => $item['profesi_nama'] ?? '-'];

            // ✅ Tambahkan mapping untuk status
            $obj->status = isset($item['status']) ? (object) ['status_nama' => $item['status']['status_nama'] ?? '-'] : (object) ['status_nama' => $item['status_nama'] ?? '-'];

            // ✅ Tambahkan mapping untuk agama (jika diperlukan)
            $obj->agama = isset($item['agama']) ? (object) ['agama_nama' => $item['agama']['agama_nama'] ?? '-'] : (object) ['agama_nama' => $item['agama_nama'] ?? '-'];

            $obj->status = isset($item['status']) ? (object) ['status_nama' => $item['status']['status_nama'] ?? '-'] : (object) ['status_nama' => $item['status_nama'] ?? '-'];

            return $obj;
        });

        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Ambil master data untuk form
     */
    private function getMasters()
    {
        $masters = [];
        $endpoints = [
            'agamas' => '/agama',
            'profesis' => '/profesi',
            'units' => '/unit',
            'jabatans' => '/jabatan',
            'golongans' => '/golongan',
            'tipes' => '/tipe',
            'statuses' => '/status',
        ];

        foreach ($endpoints as $key => $url) {
            try {
                $response = Http::withToken($this->token())
                    ->timeout(10)
                    ->get($this->apiBase . $url);

                if ($response->failed()) {
                    Log::error("API gagal ($key):", [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                }

                $masters[$key] = $response->successful() ? $response->json()['data'] ?? [] : [];
            } catch (\Exception $e) {
                Log::error("Exception $key: " . $e->getMessage());
                $masters[$key] = [];
            }
        }

        return $masters;
    }

    /**
     * Tampilkan form tambah karyawan
     */
    public function create()
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $masters = $this->getMasters();

        // Kirim semua master sebagai variabel ke view
        return view('karyawan.create', [
            'agamas' => $masters['agamas'] ?? [],
            'profesis' => $masters['profesis'] ?? [],
            'units' => $masters['units'] ?? [],
            'jabatans' => $masters['jabatans'] ?? [],
            'golongans' => $masters['golongans'] ?? [],
            'tipes' => $masters['tipes'] ?? [],
            'statuses' => $masters['statuses'] ?? [],
        ]);
    }

    /**
     * Simpan karyawan baru melalui API
     */
    public function store(Request $request)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $payload = $request->only(['kar_kode', 'user_id', 'kar_nip', 'kar_nik', 'kar_nama', 'kar_email', 'kar_hp', 'tipe_kode', 'jabatan_kode', 'unit_kode', 'status_kode', 'golongan_kode', 'agama_kode', 'profesi_kode', 'kar_gelar_depan', 'kar_gelar_belakang', 'kar_lahir_tmp', 'kar_lahir_tgl', 'kar_jekel', 'kar_alamat', 'kar_email_perusahaan', 'kar_wa', 'kar_telegram', 'kar_norek', 'kar_nobpjs', 'kar_nojamsostek', 'kar_npwp']);

        $response = Http::withToken($this->token())->post($this->apiBase . '/karyawan', $payload);

        if ($response->failed()) {
            $errorMsg = $response->json()['message'] ?? 'Gagal menambah karyawan';
            Log::error('API Karyawan Store Error: ' . $errorMsg);
            return back()->withInput()->with('error', $errorMsg);
        }

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit karyawan
     */
    public function edit($kar_kode)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $response = Http::withToken($this->token())->get($this->apiBase . '/karyawan/' . $kar_kode);

        if ($response->failed()) {
            $errorMsg = $response->json()['message'] ?? 'Gagal mengambil data karyawan';
            Log::error('API Karyawan Edit Error: ' . $errorMsg);
            return redirect()->route('karyawan.index')->with('error', $errorMsg);
        }

        $karyawan = (object) ($response->json()['data'] ?? []);
        $masters = $this->getMasters();

        return view('karyawan.edit', array_merge(['karyawan' => $karyawan], $masters));
    }

    /**
     * Update karyawan melalui API
     */
    public function update(Request $request, $kar_kode)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $payload = $request->only(['kar_kode', 'user_id', 'kar_nip', 'kar_nik', 'kar_nama', 'kar_email', 'kar_hp', 'tipe_kode', 'jabatan_kode', 'unit_kode', 'status_kode', 'golongan_kode', 'agama_kode', 'profesi_kode', 'kar_gelar_depan', 'kar_gelar_belakang', 'kar_lahir_tmp', 'kar_lahir_tgl', 'kar_jekel', 'kar_alamat', 'kar_email_perusahaan', 'kar_wa', 'kar_telegram', 'kar_norek', 'kar_nobpjs', 'kar_nojamsostek', 'kar_npwp']);

        Log::info('Update Payload:', $payload);

        $response = Http::withToken($this->token())
            ->asForm()
            ->post($this->apiBase . '/karyawan/' . $kar_kode, array_merge($payload, ['_method' => 'PUT']));

        Log::info('Update Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->failed()) {
            $errorMsg = $response->json()['message'] ?? 'Gagal update karyawan';
            Log::error('API Karyawan Update Error: ' . $errorMsg);
            return back()->withInput()->with('error', $errorMsg);
        }

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diupdate');
    }

    /**
     * Hapus karyawan melalui API
     */
    public function destroy($kar_kode)
    {
        if (!$this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $response = Http::withToken($this->token())->delete($this->apiBase . '/karyawan/' . $kar_kode);

        if ($response->failed()) {
            $errorMsg = $response->json()['message'] ?? 'Gagal menghapus karyawan';
            Log::error('API Karyawan Destroy Error: ' . $errorMsg);
            return back()->with('error', $errorMsg);
        }

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
