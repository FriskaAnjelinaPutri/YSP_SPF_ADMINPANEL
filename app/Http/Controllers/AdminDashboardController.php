<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    private function token()
    {
        return session('api_token');
    }

    public function index()
    {
        try {
            $apiUrlBase = env('API_URL');
            if (! $apiUrlBase) {
                throw new \Exception('API_URL environment variable is not set.');
            }
            $res = Http::withToken($this->token())->get($apiUrlBase.'/dashboard')->json();
            $data = $res['data'] ?? [];

            return view('dashboard', compact('data'));
        } catch (\Exception $e) {
            Log::critical('Could not connect to API for dashboard.', [
                'exception_message' => $e->getMessage(),
            ]);

            return view('dashboard', ['data' => [], 'error' => 'Could not connect to the API.']);
        }
    }
}
