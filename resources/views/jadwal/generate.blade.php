@extends('layouts.app')

@section('title', 'Generate Jadwal Kerja')

@section('content')
<div class="container-fluid">

    {{-- Custom Styles Dashboard --}}
    <style>
    /* === General === */
    body {
        background-color: #f0fdf4;
        font-family: 'Poppins', sans-serif;
    }
    h3, h5 { font-weight: 600; }
    .text-primary { color: #166534 !important; }
    .text-secondary { color: #6b7280 !important; }

    /* === Card === */
    .card {
        border-radius: 20px;
        transition: all 0.3s ease;
        background: #fff;
        border: none;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    .card-header {
        border-top-left-radius: 20px !important;
        border-top-right-radius: 20px !important;
        background: linear-gradient(90deg, #16a34a, #22c55e);
        color: #fff;
    }

    /* === Buttons === */
    .btn-rounded {
        border-radius: 50px;
        transition: transform 0.2s ease;
    }
    .btn-rounded:hover { transform: scale(1.05); }

    .btn-primary {
        background-color: #22c55e;
        border: none;
    }
    .btn-primary:hover { background-color: #16a34a; }

    /* === Alerts === */
    .alert {
        border-radius: 15px;
        padding: 12px 18px;
        font-size: 0.9rem;
    }
    .alert-success { background-color: #dcfce7; color: #166534; }
    .alert-danger { background-color: #fee2e2; color: #991b1b; }
    .alert-info { background-color: #e0f2fe; color: #075985; }

    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-magic me-2"></i>Generate Jadwal Kerja
            </h3>
            <small class="text-secondary">Sistem akan membuat jadwal kerja otomatis berdasarkan pola kerja tiap karyawan.</small>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-soft" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-soft" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar-check me-2"></i>Pilih Periode Jadwal
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('jadwal.generate.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="bulan" class="form-label">Pilih Bulan</label>
                        <select id="bulan" name="bulan" required class="form-select">
                            @php
                                $namaBulan = [
                                    'Januari','Februari','Maret','April','Mei','Juni',
                                    'Juli','Agustus','September','Oktober','November','Desember'
                                ];
                            @endphp
                            @foreach($namaBulan as $i => $b)
                                <option value="{{ $i + 1 }}" @selected($i + 1 == now()->month)>
                                    {{ $b }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tahun" class="form-label">Pilih Tahun</label>
                        <select id="tahun" name="tahun" required class="form-select">
                            @for($y = now()->year - 2; $y <= now()->year + 5; $y++)
                                <option value="{{ $y }}" @selected($y == now()->year)>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="alert alert-info mt-4">
                    <h6 class="fw-bold"><i class="bi bi-info-circle-fill"></i> Perhatian</h6>
                    <ul class="mb-0" style="font-size: 0.85rem;">
                        <li>Jadwal dibuat sesuai Pola Kerja tiap karyawan.</li>
                        <li>Pastikan karyawan memiliki Tipe Shift & Pola.</li>
                        <li>Proses tidak dapat dibatalkan setelah generate.</li>
                    </ul>
                </div>

                {{-- Button --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-rounded px-4 py-2">
                        <i class="bi bi-magic me-2"></i> Generate Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
