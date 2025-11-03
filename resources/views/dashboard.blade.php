@extends('layouts.app')

@section('title', 'Dashboard - Semen Padang Hospital')

@section('content')
<div class="container-fluid py-4">

    {{-- ================= CUSTOM STYLES ================= --}}
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: 20px;
            border: none;
        }

        .card-header {
            border-top-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
        }

        .shadow-soft {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .dashboard-title {
            color: #006d77;
        }

        .shortcut-card {
            transition: all 0.25s ease-in-out;
            border: none;
        }

        .shortcut-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .bg-sp-primary { background-color: #118ab2 !important; }
        .bg-sp-success { background-color: #06d6a0 !important; }
        .bg-sp-warning { background-color: #ffd166 !important; color: #333 !important; }
        .bg-sp-danger  { background-color: #ef476f !important; }

        .no-data {
            text-align: center;
            color: #6c757d;
            padding: 20px 0;
        }

        .no-data i {
            font-size: 2rem;
            display: block;
            margin-bottom: 8px;
            color: #adb5bd;
        }

        /* Card hover effect for info cards */
        .info-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        /* Chart responsiveness */
        canvas {
            width: 100% !important;
        }
    </style>

    {{-- ================= GREETING ================= --}}
    <div class="mb-4">
        @php
            $hour = now()->format('H');
            if ($hour < 12) {
                $greeting = 'Good Morning';
            } elseif ($hour < 18) {
                $greeting = 'Good Afternoon';
            } else {
                $greeting = 'Good Evening';
            }
            $adminName = session('user')['name'] ?? 'Admin';
        @endphp

        <h4 class="fw-bold dashboard-title">{{ $greeting }}, {{ $adminName }} 👋</h4>
        <p class="text-muted">Welcome back to <strong>Semen Padang Hospital</strong> attendance dashboard.</p>
    </div>

    {{-- ================= SHORTCUT MENU ================= --}}
    <div class="row g-3 mb-4 text-center">
        <div class="col-6 col-md-3">
            <a href="{{ route('karyawan.index') }}" class="card shortcut-card shadow-soft text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-sp-primary text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <h6 class="fw-semibold mb-0">Employees</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('jadwal.index') }}" class="card shortcut-card shadow-soft text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-sp-success text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-calendar-week fs-3"></i>
                    </div>
                    <h6 class="fw-semibold mb-0">Jadwal Kerja</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('pola.index') }}" class="card shortcut-card shadow-soft text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-sp-warning text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-clock fs-3"></i>
                    </div>
                    <h6 class="fw-semibold mb-0">Pola Jam Kerja</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('tipe.index') }}" class="card shortcut-card shadow-soft text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-sp-danger text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-grid fs-3"></i>
                    </div>
                    <h6 class="fw-semibold mb-0">Tipe Shift</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="#" class="card shortcut-card shadow-soft text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-sp-success text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-calendar-check fs-3"></i>
                    </div>
                    <h6 class="fw-semibold mb-0">Attendance</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="#" class="card shortcut-card shadow-soft text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-sp-warning text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <h6 class="fw-semibold mb-0">Overtime</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="#" class="card shortcut-card shadow-soft text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-sp-danger text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-calendar-x fs-3"></i>
                    </div>
                    <h6 class="fw-semibold mb-0">Leave</h6>
                </div>
            </a>
        </div>
    </div>

    {{-- ================= INFO CARDS ================= --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card bg-sp-primary text-white shadow-soft info-card h-100">
                <div class="card-body text-center">
                    <h6>Total Employees</h6>
                    <h2 class="fw-bold mb-0">{{ $totalKaryawan ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-sp-success text-white shadow-soft info-card h-100">
                <div class="card-body text-center">
                    <h6>Today’s Attendance</h6>
                    <h2 class="fw-bold mb-0">{{ $absensiHariIni ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-sp-warning text-white shadow-soft info-card h-100">
                <div class="card-body text-center">
                    <h6>Overtime This Month</h6>
                    <h2 class="fw-bold mb-0">{{ $totalLembur ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-sp-danger text-white shadow-soft info-card h-100">
                <div class="card-body text-center">
                    <h6>Leave Requests</h6>
                    <h2 class="fw-bold mb-0">{{ $totalCuti ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= CHARTS ================= --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-soft border-0 h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-graph-up text-sp-primary me-2"></i> Attendance Statistics
                </div>
                <div class="card-body">
                    <canvas id="absensiChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-soft border-0 h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-bar-chart-line text-sp-warning me-2"></i> Overtime Statistics
                </div>
                <div class="card-body">
                    <canvas id="lemburChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= LATEST EMPLOYEES ================= --}}
    <div class="card shadow-soft border-0 mb-5">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-person-lines-fill text-sp-primary me-2"></i> Latest Employees
        </div>
        <div class="card-body">
            @forelse($karyawanTerbaru ?? [] as $karyawan)
                <div class="d-flex align-items-center border-bottom py-2">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:45px; height:45px;">
                        <i class="bi bi-person fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $karyawan['kar_nama'] }}</h6>
                        <small class="text-muted">{{ $karyawan['kar_email'] }}</small><br>
                        <span class="badge bg-sp-primary">{{ $karyawan['jabatan_kode'] ?? 'N/A' }}</span>
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <i class="bi bi-emoji-frown"></i>
                    <p>No data available</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Attendance Chart
    const absensiCtx = document.getElementById('absensiChart').getContext('2d');
    new Chart(absensiCtx, {
        type: 'line',
        data: {
            labels: @json($absensiLabels ?? []),
            datasets: [{
                label: 'Attendance',
                data: @json($absensiData ?? []),
                borderColor: '#118ab2',
                backgroundColor: 'rgba(17,138,178,0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: true, position: 'bottom' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Overtime Chart
    const lemburCtx = document.getElementById('lemburChart').getContext('2d');
    new Chart(lemburCtx, {
        type: 'bar',
        data: {
            labels: @json($lemburLabels ?? []),
            datasets: [{
                label: 'Overtime',
                data: @json($lemburData ?? []),
                backgroundColor: '#ffd166'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: true, position: 'bottom' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
