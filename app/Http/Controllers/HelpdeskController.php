<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class HelpdeskController extends Controller
{
    private function apiBase()
    {
        return env('API_URL', 'http://127.0.0.1:8000/api');
    }

    private function token()
    {
        return Session::get('api_token');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        try {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->get($this->apiBase() . '/helpdesk', [
                    'search' => $search,
                ]);

            if ($response->successful()) {
                $helpdesks = $response->json()['data'];
                return view('helpdesk.index', compact('helpdesks', 'search'));
            }

            return back()->with('error', 'Gagal mengambil data helpdesk dari API.');
        } catch (\Exception $e) {
            Log::error('Error fetching helpdesk tickets: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat terhubung ke server API.');
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withToken($this->token())->get($this->apiBase() . '/helpdesk/' . $id);

            if ($response->successful()) {
                $helpdesk = $response->json()['data'];
                return view('helpdesk.edit', compact('helpdesk'));
            }

            return back()->with('error', 'Gagal mengambil data tiket helpdesk.');
        } catch (\Exception $e) {
            Log::error('Error fetching helpdesk ticket for edit: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat terhubung ke server API.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|string',
            'prioritas' => 'required|string',
            'kategori' => 'required|string',
        ]);

        try {
            $response = Http::withToken($this->token())->put($this->apiBase() . '/helpdesk/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->route('helpdesk.index')->with('success', 'Data tiket helpdesk berhasil diperbarui.');
            }

            return back()->with('error', 'Gagal memperbarui data tiket helpdesk: ' . $response->json('message', 'Unknown error'))->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating helpdesk ticket: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat terhubung ke server API.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withToken($this->token())->delete($this->apiBase() . '/helpdesk/' . $id);

            if ($response->successful()) {
                return redirect()->route('helpdesk.index')->with('success', 'Tiket helpdesk berhasil dihapus.');
            }

            return back()->with('error', 'Gagal menghapus tiket helpdesk: ' . $response->json('message', 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Error deleting helpdesk ticket: ' . $e->getMessage());
            return back()->with('error', 'Tidak dapat terhubung ke server API.');
        }
    }
}
