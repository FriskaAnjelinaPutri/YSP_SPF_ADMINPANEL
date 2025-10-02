<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminKaryawanController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api';

    private function token() {
        return session('api_token');
    }

    public function index()
    {
        $res = Http::withToken($this->token())->get($this->apiBase.'/karyawan')->json();
        $karyawans = $res['data'] ?? [];
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
