<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('api_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/dashboard');
        $data = $response->json();

        return view('dashboard', compact('data'));
    }
}
