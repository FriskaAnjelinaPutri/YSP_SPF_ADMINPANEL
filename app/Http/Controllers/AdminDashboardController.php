<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class AdminDashboardController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api';

    private function token()
    {
        return session('api_token');
    }

    public function index()
    {
        $res = Http::withToken($this->token())->get($this->apiBase.'/dashboard')->json();
        $data = $res['data'] ?? [];

        return view('dashboard', compact('data'));
    }
}
