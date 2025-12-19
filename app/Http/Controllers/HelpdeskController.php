<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HelpdeskController extends Controller
{
    private function token()
    {
        return session('api_token');
    }

    private function getApiUrl()
    {
        $apiUrlBase = env('API_URL');
        if (!$apiUrlBase) {
            throw new \Exception('API_URL environment variable is not set.');
        }
        return $apiUrlBase;
    }

    private function getStatusOptions()
    {
        return ['Open', 'In Progress', 'Resolved', 'Closed'];
    }

    public function index(Request $request)
    {
        try {
            $apiUrlBase = $this->getApiUrl();
            $periode = $request->input('periode', Carbon::now()->format('Y-m'));
            $url = $apiUrlBase.'/helpdesk/'.$periode;

            Log::info('Mengambil data helpdesk dari API', ['url' => $url]);
            $response = Http::withToken($this->token())->get($url);

            if ($response->failed()) {
                Log::error('Gagal ambil data helpdesk', ['status' => $response->status(), 'body' => $response->body()]);
                return view('helpdesk.index', ['helpdesks' => [], 'periode' => $periode, 'error' => 'Gagal mengambil data helpdesk dari API.']);
            }

            $helpdesks = json_decode(json_encode($response->json()['data'] ?? []));
            return view('helpdesk.index', compact('helpdesks', 'periode'));

        } catch (\Exception $e) {
            Log::critical('Could not connect to API for helpdesk index.', ['exception_message' => $e->getMessage()]);
            return view('helpdesk.index', ['helpdesks' => [], 'periode' => $periode ?? Carbon::now()->format('Y-m'), 'error' => 'Could not connect to the API.']);
        }
    }

    public function show($id)
    {
        try {
            $apiUrlBase = $this->getApiUrl();
            $response = Http::withToken($this->token())->get($apiUrlBase."/helpdesk/detail/{$id}");

            if ($response->failed()) {
                return response()->json(['error' => 'Tiket tidak ditemukan atau gagal mengambil data.'], 404);
            }
            return $response->json();

        } catch (\Exception $e) {
            Log::critical('Could not connect to API for helpdesk show.', ['id' => $id, 'exception_message' => $e->getMessage()]);
            return response()->json(['error' => 'Could not connect to the API.'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $apiUrlBase = $this->getApiUrl();
            $response = Http::withToken($this->token())->get($apiUrlBase."/helpdesk/detail/{$id}");

            if ($response->failed()) {
                return redirect()->route('helpdesk.index')->with('error', 'Tiket tidak ditemukan.');
            }

            $ticket = json_decode(json_encode($response->json()['data'] ?? []));
            $statuses = $this->getStatusOptions();

            return view('helpdesk.edit', compact('ticket', 'statuses'));

        } catch (\Exception $e) {
            Log::critical('Could not connect to API for helpdesk edit.', ['id' => $id, 'exception_message' => $e->getMessage()]);
            return redirect()->route('helpdesk.index')->with('error', 'Could not connect to the API.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $apiUrlBase = $this->getApiUrl();
            $validated = $request->validate(['status' => 'required|string']);

            $response = Http::withToken($this->token())->put($apiUrlBase."/helpdesk/status/{$id}", [
                'status' => $validated['status']
            ]);

            if ($response->failed()) {
                return back()->with('error', 'Gagal memperbarui status tiket.');
            }

            return redirect()->route('helpdesk.index')->with('message', 'Status tiket berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::critical('Could not connect to API for helpdesk update.', ['id' => $id, 'exception_message' => $e->getMessage()]);
            return back()->with('error', 'Could not connect to the API.');
        }
    }

    public function destroy($id)
    {
        try {
            $apiUrlBase = $this->getApiUrl();
            $response = Http::withToken($this->token())->delete($apiUrlBase."/helpdesk/{$id}");

            if ($response->failed()) {
                return redirect()->route('helpdesk.index')->with('error', 'Gagal menghapus tiket.');
            }
            return redirect()->route('helpdesk.index')->with('message', 'Tiket berhasil dihapus.');

        } catch (\Exception $e) {
            Log::critical('Could not connect to API for helpdesk destroy.', ['id' => $id, 'exception_message' => $e->getMessage()]);
            return redirect()->route('helpdesk.index')->with('error', 'Could not connect to the API.');
        }
    }
}