<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminKaryawanController extends Controller
{
    private function token()
    {
        return session('api_token');
    }

    public function index(Request $request)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (! $apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }

            $search = $request->input('search');
            $url = $apiUrlBase.'/karyawan';

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
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for karyawan index.', [
                'exception_message' => $e->getMessage(),
            ]);

            return view('karyawan.index', ['karyawans' => [], 'error' => 'Could not connect to the API.']);
        }
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function store(Request $request)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (! $apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }
            $res = Http::withToken($this->token())->post($apiUrlBase.'/karyawan', $request->all())->json();

            return redirect('/karyawan')->with('message', $res['message'] ?? 'Data berhasil dibuat');
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for karyawan store.', [
                'exception_message' => $e->getMessage(),
            ]);

            return redirect('/karyawan')->with('error', 'Could not connect to the API.');
        }
    }

    public function edit($kar_kode)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (! $apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }
            $res = Http::withToken($this->token())->get($apiUrlBase."/karyawan/{$kar_kode}")->json();
            $karyawan = $res['data'] ?? [];

            return view('karyawan.edit', compact('karyawan'));
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for karyawan edit.', [
                'exception_message' => $e->getMessage(),
            ]);

            return redirect('/karyawan')->with('error', 'Could not connect to the API.');
        }
    }

    public function update(Request $request, $kar_kode)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (! $apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }
            $res = Http::withToken($this->token())->put($apiUrlBase."/karyawan/{$kar_kode}", $request->all())->json();

            return redirect('/karyawan')->with('message', $res['message'] ?? 'Data berhasil diupdate');
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for karyawan update.', [
                'exception_message' => $e->getMessage(),
            ]);

            return redirect('/karyawan')->with('error', 'Could not connect to the API.');
        }
    }

    public function destroy($kar_kode)
    {
        try {
            $apiUrlBase = env('API_URL');
            if (! $apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }
            $res = Http::withToken($this->token())->delete($apiUrlBase."/karyawan/{$kar_kode}")->json();

            return redirect('/karyawan')->with('message', $res['message'] ?? 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for karyawan destroy.', [
                'exception_message' => $e->getMessage(),
            ]);

            return redirect('/karyawan')->with('error', 'Could not connect to the API.');
        }
    }
}
