<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api';

    private function token()
    {
        return session('api_token');
    }

    public function index(Request $request)
{
    $periode = $request->periode ?? date('Y-m'); // default ke bulan ini (contoh: 2025-10)

    $response = Http::withToken($this->token())->get("{$this->apiBase}/absensi/{$periode}");

    if ($response->failed()) {
        return back()->with('error', 'Gagal memuat data absensi dari API.');
    }

    $absensiData = $response->json()['data'] ?? [];
    $departments = ['HR', 'IT', 'Finance', 'Medical', 'Security']; // contoh statis

    return view('absensi.index', compact('absensiData', 'departments', 'periode'));
}

}
