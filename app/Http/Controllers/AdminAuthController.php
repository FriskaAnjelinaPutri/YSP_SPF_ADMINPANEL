<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminAuthController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api';

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $response = Http::post($this->apiBase.'/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $res = $response->json();

        if (isset($res['data']['access_token'])) {
            session([
                'api_token' => $res['data']['access_token'],
                'user' => $res['data']['user'],
            ]);

            return redirect('/');
        }

        return back()->with('error', $res['message'] ?? 'Login gagal');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect('/login');
    }
}
