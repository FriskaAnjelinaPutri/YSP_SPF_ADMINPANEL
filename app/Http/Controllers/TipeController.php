<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TipeController extends Controller
{
    private function apiBase()
    {
        return env('API_URL');
    }

    private function token()
    {
        return Session::get('api_token');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/tipe');

            if ($response->failed()) {
                Log::error('Gagal mengambil data tipe', ['body' => $response->body()]);

                return view('tipe.index', [
                    'tipes' => [],
                    'error' => 'Gagal mengambil data tipe dari API',
                ]);
            }

            $tipes = $response->json()['data'] ?? [];

            return view('tipe.index', compact('tipes'));

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data tipe', ['error' => $e->getMessage()]);

            return view('tipe.index', [
                'tipes' => [],
                'error' => 'Terjadi kesalahan saat mengambil data',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipe.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'tipe_nama' => 'required|string|max:100',
            'tipe_aktif' => 'required|boolean',
        ], [
            'tipe_nama.required' => 'Nama tipe wajib diisi',
            'tipe_nama.max' => 'Nama tipe maksimal 100 karakter',
            'tipe_aktif.required' => 'Status aktif wajib dipilih',
        ]);

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->post($this->apiBase().'/tipe', [
                    'tipe_nama' => $request->tipe_nama,
                    'tipe_aktif' => $request->tipe_aktif,
                ]);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? null;
                $kode = $data['tipe_kode'] ?? '';

                return redirect()->route('tipe.index')
                    ->with('success', 'Data tipe berhasil ditambahkan dengan kode: '.$kode);
            }

            $error = $response->json()['message'] ?? 'Gagal menyimpan data tipe';

            return back()->withInput()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat menyimpan tipe', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kode)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/tipe/'.$kode);

            if ($response->successful()) {
                $tipe = $response->json()['data'] ?? null;

                return view('tipe.show', compact('tipe'));
            }

            return redirect()->route('tipe.index')
                ->with('error', 'Data tipe tidak ditemukan');

        } catch (\Exception $e) {
            Log::error('Error saat mengambil detail tipe', ['error' => $e->getMessage()]);

            return redirect()->route('tipe.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $kode)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase().'/tipe/'.$kode);

            if ($response->successful()) {
                $tipe = $response->json()['data'] ?? null;

                return view('tipe.edit', compact('tipe'));
            }

            return redirect()->route('tipe.index')
                ->with('error', 'Data tipe tidak ditemukan');

        } catch (\Exception $e) {
            Log::error('Error saat mengambil data tipe untuk edit', ['error' => $e->getMessage()]);

            return redirect()->route('tipe.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $kode)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        $request->validate([
            'tipe_nama' => 'required|string|max:100',
            'tipe_aktif' => 'required|boolean',
        ], [
            'tipe_nama.required' => 'Nama tipe wajib diisi',
            'tipe_nama.max' => 'Nama tipe maksimal 100 karakter',
            'tipe_aktif.required' => 'Status aktif wajib dipilih',
        ]);

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->put($this->apiBase().'/tipe/'.$kode, [
                    'tipe_nama' => $request->tipe_nama,
                    'tipe_aktif' => $request->tipe_aktif,
                ]);

            if ($response->successful()) {
                return redirect()->route('tipe.index')
                    ->with('success', 'Data tipe berhasil diperbarui');
            }

            $error = $response->json()['message'] ?? 'Gagal memperbarui data tipe';

            return back()->withInput()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat update tipe', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $kode)
    {
        if (! $this->token()) {
            return redirect()->route('login')->with('error', 'Token autentikasi tidak ditemukan.');
        }

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->delete($this->apiBase().'/tipe/'.$kode);

            if ($response->successful()) {
                return redirect()->route('tipe.index')
                    ->with('success', 'Data tipe berhasil dihapus');
            }

            $error = $response->json()['message'] ?? 'Gagal menghapus data tipe';

            return back()->with('error', $error);

        } catch (\Exception $e) {
            Log::error('Error saat menghapus tipe', ['error' => $e->getMessage()]);

            return back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
}
