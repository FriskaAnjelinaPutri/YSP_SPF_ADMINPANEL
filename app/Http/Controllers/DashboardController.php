<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('api_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/dashboard');

        if ($response->failed()) {
            Log::error('API Dashboard gagal: ' . $response->body());
            return view('dashboard', [
                'totalKaryawan' => 0,
                'absensiHariIni' => 0,
                'totalLembur' => 0,
                'totalCuti' => 0,
                'karyawanTerbaru' => [],
                'absensiLabels' => [],
                'absensiData' => [],
            ]);
        }

        $data = $response->json();

        return view('dashboard', [
            'totalKaryawan' => $data['totalKaryawan'] ?? 0,
            'absensiHariIni' => $data['absensiHariIni'] ?? 0,
            'totalLembur' => $data['totalLembur'] ?? 0,
            'totalCuti' => $data['totalCuti'] ?? 0,
            'karyawanTerbaru' => $data['karyawanTerbaru'] ?? [],
            'absensiLabels' => $data['absensiLabels'] ?? [],
            'absensiData' => $data['absensiData'] ?? [],
        ]);
    }
}
