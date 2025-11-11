<?php

use Illuminate\Support\Facades\Session;

it('redirects to login if not authenticated', function () {
    $response = $this->get(route('jadwal.hasil'));
    $response->assertRedirect(route('login'));
});

it('returns a successful response for hasilGenerate when authenticated', function () {
    // Simulate authenticated user by setting a dummy API token and user data in the session
    Session::put('api_token', 'dummy_token');
    Session::put('user', ['role' => 'admin', 'name' => 'Admin User']);

    $response = $this->get(route('jadwal.hasil'));

    $response->assertStatus(200);
    $response->assertViewIs('jadwal.hasil');
});
