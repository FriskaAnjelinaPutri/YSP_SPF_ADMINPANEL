<?php

use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolaController;
use App\Http\Controllers\TipeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminKaryawanController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Manajemen Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

    // Manajemen Jadwal
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    // Manajemen Pola
    Route::get('/pola', [PolaController::class, 'index'])->name('pola.index');
    Route::get('/pola/create', [PolaController::class, 'create'])->name('pola.create');
    Route::post('/pola', [PolaController::class, 'store'])->name('pola.store');
    Route::get('/pola/{kode}/edit', [PolaController::class, 'edit'])->name('pola.edit');
    Route::put('/pola/{kode}', [PolaController::class, 'update'])->name('pola.update');
    Route::delete('/pola/{kode}', [PolaController::class, 'destroy'])->name('pola.destroy');

    // Manajemen Tipe
    Route::get('/tipe', [TipeController::class, 'index'])->name('tipe.index');
    Route::get('/tipe/create', [TipeController::class, 'create'])->name('tipe.create');
    Route::post('/tipe', [TipeController::class, 'store'])->name('tipe.store');
    Route::get('/tipe/{kode}', [TipeController::class, 'show'])->name('tipe.show');
    Route::get('/tipe/{kode}/edit', [TipeController::class, 'edit'])->name('tipe.edit');
    Route::put('/tipe/{kode}', [TipeController::class, 'update'])->name('tipe.update');
    Route::delete('/tipe/{kode}', [TipeController::class, 'destroy'])->name('tipe.destroy');
});
