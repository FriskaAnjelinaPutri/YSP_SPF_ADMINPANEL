<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LokasiController extends Controller
{
    private function token()
    {
        return session('api_token');
    }

    private function getApiUrl($path)
    {
        $apiUrlBase = env('API_URL');
        if (!$apiUrlBase) {
            throw new \Exception('API_URL environment variable is not set.');
        }
        return rtrim($apiUrlBase, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Menampilkan halaman read-only untuk detail lokasi.
     */
    public function show()
    {
        try {
            $url = $this->getApiUrl('lokasi');
            $response = Http::withToken($this->token())->get($url);

            if ($response->failed()) {
                $error = 'Gagal mengambil data lokasi dari API. Status: ' . $response->status();
                Log::error('Gagal ambil data lokasi', ['status' => $response->status(), 'body' => $response->body()]);
                return view('lokasi.show', ['lokasi' => null, 'error' => $error]);
            }

            $lokasi = json_decode(json_encode($response->json()['data'] ?? null));

            return view('lokasi.show', compact('lokasi'));

        } catch (\Exception $e) {
            Log::critical('Could not connect to API for lokasi show.', ['exception_message' => $e->getMessage()]);
            return view('lokasi.show', ['lokasi' => null, 'error' => 'Could not connect to the API.']);
        }
    }

    /**
     * Menampilkan form untuk mengedit lokasi.
     */
    public function edit()
    {
        try {
            $url = $this->getApiUrl('lokasi');
            $response = Http::withToken($this->token())->get($url);

            if ($response->failed()) {
                $error = 'Gagal mengambil data lokasi dari API. Status: ' . $response->status();
                Log::error('Gagal ambil data lokasi', ['status' => $response->status(), 'body' => $response->body()]);
                return view('lokasi.edit', ['lokasi' => null, 'error' => $error]);
            }

            $lokasi = json_decode(json_encode($response->json()['data'] ?? null));

            return view('lokasi.edit', compact('lokasi'));

        } catch (\Exception $e) {
            Log::critical('Could not connect to API for lokasi edit.', ['exception_message' => $e->getMessage()]);
            return view('lokasi.edit', ['lokasi' => null, 'error' => 'Could not connect to the API.']);
        }
    }

    /**
     * Memperbarui data lokasi.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
        ]);

        try {
            $url = $this->getApiUrl('hcm-ta-lokasi');
            $response = Http::withToken($this->token())->put($url, $validated);

            if ($response->failed()) {
                Log::error('Gagal update data lokasi', ['status' => $response->status(), 'body' => $response->body()]);
                $apiError = $response->json()['message'] ?? 'Gagal memperbarui data lokasi di API.';
                return back()->with('error', $apiError);
            }

            return redirect()->route('lokasi.show')->with('success', 'Data lokasi kantor berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::critical('Could not connect to API for lokasi update.', ['exception_message' => $e->getMessage()]);
            return back()->with('error', 'Could not connect to the API.');
        }
    }
}
