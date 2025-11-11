<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminKaryawanController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api';

    private function token()
    {
        return session('api_token');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $url = $this->apiBase.'/karyawan';

        $queryParams = [];
        if ($search) {
            // Menggunakan 'nama' sebagai parameter pencarian, asumsi API mendukung ini
            $queryParams['nama'] = $search;
        }

        Log::info('Mengambil data karyawan dari API', ['url' => $url, 'params' => $queryParams]);

        $response = Http::withToken($this->token())->get($url, $queryParams);

        if ($response->failed()) {
            Log::error('Gagal ambil data karyawan', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return view('karyawan.index', [
                'karyawans' => [],
                'error' => 'Gagal mengambil data karyawan dari API.',
            ]);
        }

        $karyawansData = $response->json()['data'] ?? [];
        $karyawans = json_decode(json_encode($karyawansData));

        return view('karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function store(Request $request)
    {
        $res = Http::withToken($this->token())->post($this->apiBase.'/karyawan', $request->all())->json();

        return redirect('/karyawan')->with('message', $res['message'] ?? 'Data berhasil dibuat');
    }

    public function edit($kar_kode)
    {
        $res = Http::withToken($this->token())->get($this->apiBase."/karyawan/{$kar_kode}")->json();
        $karyawan = $res['data'] ?? [];

        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $kar_kode)
    {
        $res = Http::withToken($this->token())->put($this->apiBase."/karyawan/{$kar_kode}", $request->all())->json();

        return redirect('/karyawan')->with('message', $res['message'] ?? 'Data berhasil diupdate');
    }

    public function destroy($kar_kode)
    {
        $res = Http::withToken($this->token())->delete($this->apiBase."/karyawan/{$kar_kode}")->json();

        return redirect('/karyawan')->with('message', $res['message'] ?? 'Data berhasil dihapus');
    }
}
