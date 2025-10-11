<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api'; // API endpoint

    private function token()
    {
        return session('api_token');
    }

    /**
     * Tampilkan halaman absensi
     */
    public function index(Request $request)
    {
        $periode = $request->periode ?? date('Y-m'); // default bulan ini
        $departmentFilter = $request->department;

        // Panggil API
        $response = Http::withToken($this->token())->get("{$this->apiBase}/absensi/{$periode}");

        if ($response->status() === 401) {
            return redirect()->route('login')->with('error', 'Token expired, silakan login kembali.');
        }

        if ($response->failed()) {
            return back()->with('error', 'Gagal memuat data absensi dari API.');
        }

        $absensiData = $response->json()['data'] ?? [];

        // Format tanggal & ambil nama karyawan, department
        foreach ($absensiData as &$item) {
            // Cek karyawan dulu, jangan langsung akses array
            $item['nama_karyawan'] = $item['karyawan']['nama'] ?? '-';
            $item['department']   = $item['karyawan']['department'] ?? '-';

            // Format tanggal jika ada
            if (!empty($item['tanggal'])) {
                try {
                    $item['tanggal'] = Carbon::parse($item['tanggal'])->format('d-m-Y');
                } catch (\Exception $e) {
                    $item['tanggal'] = $item['tanggal'];
                }
            }

            // Pastikan status & keterangan ada
            $item['status']    = $item['status'] ?? '-';
            $item['keterangan'] = $item['keterangan'] ?? '-';
        }
        unset($item);

        // Filter departemen jika dipilih
        if ($departmentFilter) {
            $absensiData = array_filter($absensiData, fn($item) => ($item['department'] ?? '') === $departmentFilter);
        }

        // Daftar departemen (contoh statis, bisa diganti API)
        $departments = ['HR', 'IT', 'Finance', 'Medical', 'Security'];

        return view('absensi.index', compact('absensiData', 'departments', 'periode'));
    }
}
