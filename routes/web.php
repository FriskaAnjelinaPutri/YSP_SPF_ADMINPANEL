<?php

use App\Http\Middleware\AdminAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\PolaController;
use App\Http\Controllers\TipeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LemburController;
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

    // Absensi Management
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/{absensi}', [AbsensiController::class, 'show'])->name('absensi.show');
    Route::get('/absensi/{absensi}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
    Route::put('/absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('/absensi/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');

    // Cuti Management
    Route::get('/cuti', [CutiController::class, 'index'])->name('cuti.index');
    Route::get('/cuti/create', [CutiController::class, 'create'])->name('cuti.create');
    Route::post('/cuti', [CutiController::class, 'store'])->name('cuti.store');
    Route::get('/cuti/{cuti}', [CutiController::class, 'show'])->name('cuti.show');
    Route::get('/cuti/{cuti}/edit', [CutiController::class, 'edit'])->name('cuti.edit');
    Route::put('/cuti/{cuti}', [CutiController::class, 'update'])->name('cuti.update');
    Route::delete('/cuti/{cuti}', [CutiController::class, 'destroy'])->name('cuti.destroy');
    Route::post('/cuti/{id}/approve', [CutiController::class, 'approve'])->name('cuti.approve');
    Route::post('/cuti/{id}/reject', [CutiController::class, 'reject'])->name('cuti.reject');

    // Lembur Management
    Route::get('/lembur', [LemburController::class, 'index'])->name('lembur.index');
    Route::get('/lembur/create', [LemburController::class, 'create'])->name('lembur.create');
    Route::post('/lembur', [LemburController::class, 'store'])->name('lembur.store');
    Route::get('/lembur/{lembur}', [LemburController::class, 'show'])->name('lembur.show');
    Route::get('/lembur/{lembur}/edit', [LemburController::class, 'edit'])->name('lembur.edit');
    Route::put('/lembur/{lembur}', [LemburController::class, 'update'])->name('lembur.update');
    Route::delete('/lembur/{lembur}', [LemburController::class, 'destroy'])->name('lembur.destroy');
    Route::post('/lembur/{id}/approve', [LemburController::class, 'approve'])->name('lembur.approve');
    Route::post('/lembur/{id}/reject', [LemburController::class, 'reject'])->name('lembur.reject');

    // Manajemen Karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

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
