<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    private function apiBase()
    {
        return env('API_URL', 'http://127.0.0.1:8000/api');
    }

    private function token()
    {
        return Session::get('api_token');
    }

    public function index()
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            Log::info('Dashboard - Calling API', [
                'url' => $this->apiBase().'/dashboard',
                'token' => substr($this->token(), 0, 20).'...',
            ]);

            $response = Http::withToken($this->token())
                ->acceptJson()
                ->timeout(30)
                ->get($this->apiBase().'/dashboard');

            Log::info('Dashboard - API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            if ($response->failed()) {
                Log::error('Dashboard API gagal', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return view('dashboard', [
                    'totalKaryawan' => 0,
                    'absensiHariIni' => 0,
                    'totalLembur' => 0,
                    'totalCuti' => 0,
                    'karyawanTerbaru' => [],
                    'absensiLabels' => [],
                    'absensiData' => [],
                    'error' => 'Gagal mengambil data dashboard dari API',
                ]);
            }

            $responseData = $response->json();

            // Cek struktur response
            if (! isset($responseData['success']) || ! $responseData['success']) {
                Log::warning('Dashboard - Response format tidak sesuai', [
                    'response' => $responseData,
                ]);
            }

            // Ambil data dari response
            $data = $responseData['data'] ?? $responseData;

            Log::info('Dashboard - Data yang akan ditampilkan', $data);

            return view('dashboard', [
                'totalKaryawan' => $data['totalKaryawan'] ?? 0,
                'absensiHariIni' => $data['absensiHariIni'] ?? 0,
                'totalLembur' => $data['totalLembur'] ?? 0,
                'totalCuti' => $data['totalCuti'] ?? 0,
                'karyawanTerbaru' => $data['karyawanTerbaru'] ?? [],
                'absensiLabels' => $data['absensiLabels'] ?? [],
                'absensiData' => $data['absensiData'] ?? [],
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard - Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return view('dashboard', [
                'totalKaryawan' => 0,
                'absensiHariIni' => 0,
                'totalLembur' => 0,
                'totalCuti' => 0,
                'karyawanTerbaru' => [],
                'absensiLabels' => [],
                'absensiData' => [],
                'error' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        }
    }
}
