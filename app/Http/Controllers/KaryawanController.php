<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KaryawanController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api';

    private function token()
    {
        return session('api_token');
    }

    public function index()
    {
        $response = Http::withToken($this->token())->get($this->apiBase.'/karyawan');
        $data = $response->json();
        return view('karyawan.index', ['karyawans' => $data['data'] ?? []]);
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function store(Request $request)
    {
        // kirim field sesuai model HcmTbKaryawan
        $payload = $request->only([
            'kar_kode','user_id','kar_nip','kar_nik','kar_nama','kar_email','kar_hp','tipe_kode','jabatan_kode','unit_kode','status_kode','golongan_kode','agama_kode','profesi_kode'
        ]);

        $response = Http::withToken($this->token())->post($this->apiBase.'/karyawan', $payload);

        if ($response->failed()) {
            return back()->with('error', $response->json()['message'] ?? 'Gagal menambah karyawan');
        }
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit($kar_kode)
    {
        $response = Http::withToken($this->token())->get($this->apiBase.'/karyawan/'.$kar_kode);
        $karyawan = $response->json()['data'] ?? [];
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $kar_kode)
    {
        $payload = $request->only([
            'kar_kode','user_id','kar_nip','kar_nik','kar_nama','kar_email','kar_hp','tipe_kode','jabatan_kode','unit_kode','status_kode','golongan_kode','agama_kode','profesi_kode'
        ]);

        $response = Http::withToken($this->token())->put($this->apiBase.'/karyawan/'.$kar_kode, $payload);

        if ($response->failed()) {
            return back()->with('error', $response->json()['message'] ?? 'Gagal update karyawan');
        }
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diupdate');
    }

    public function destroy($kar_kode)
    {
        $response = Http::withToken($this->token())->delete($this->apiBase.'/karyawan/'.$kar_kode);
        if ($response->failed()) {
            return back()->with('error', $response->json()['message'] ?? 'Gagal menghapus karyawan');
        }
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
