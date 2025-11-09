@extends('layouts.app')

@section('title', 'Dashboard - Semen Padang Hospital')

@section('content')
<div class="container-fluid py-4 px-5">

    {{-- ================= CUSTOM STYLES (Modern Green Theme) ================= --}}
    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }

        .dashboard-title { color: #166534; }
        .card {
            border-radius: 1rem;
            border: none;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1),
                        0 2px 4px -2px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1),
                        0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            color: #1f2937;
            padding: 1.25rem;
        }

        .shortcut-card .icon-bg {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #dcfce7;
            color: #22c55e;
            font-size: 1.75rem;
            transition: all 0.3s ease;
        }

        .shortcut-card:hover .icon-bg { transform: scale(1.1); }
        .info-card { padding: 1.5rem; }
        .info-card .icon { font-size: 2rem; color: #16a34a; }
        .info-card h2 { color: #166534; }
        .badge-custom {
            background-color: #dcfce7;
            color: #166534;
            font-weight: 500;
            padding: 0.5em 0.75em;
        }
        canvas { width: 100% !important; }
    </style>

    {{-- ================= GREETING ================= --}}
    <div class="mb-8">
        @php
            $hour = now()->format('H');
            $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
            $adminName = session('user')['name'] ?? 'Admin';
        @endphp

        <h2 class="fw-bold dashboard-title">{{ $greeting }}, {{ $adminName }} 👋</h2>
        <p class="text-muted">Welcome back! Here's a quick overview of the attendance at
            <strong>Semen Padang Hospital</strong>.
        </p>
    </div>

    {{-- ================= INFO CARDS ================= --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card info-card h-100">
                <div class="d-flex align-items-center">
                    <div class="icon me-3"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <h6 class="text-muted mb-1">Total Employees</h6>
                        <h2 class="fw-bold mb-0">{{ $totalKaryawan }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card h-100">
                <div class="d-flex align-items-center">
                    <div class="icon me-3"><i class="bi bi-calendar-check-fill"></i></div>
                    <div>
                        <h6 class="text-muted mb-1">Today’s Attendance</h6>
                        <h2 class="fw-bold mb-0">{{ $absensiHariIni }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card h-100">
                <div class="d-flex align-items-center">
                    <div class="icon me-3"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <h6 class="text-muted mb-1">Overtime This Month</h6>
                        <h2 class="fw-bold mb-0">{{ $totalLembur }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card h-100">
                <div class="d-flex align-items-center">
                    <div class="icon me-3"><i class="bi bi-calendar-x-fill"></i></div>
                    <div>
                        <h6 class="text-muted mb-1">Leave Requests</h6>
                        <h2 class="fw-bold mb-0">{{ $totalCuti }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= CHARTS & SHORTCUTS ================= --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-graph-up-arrow me-2" style="color: #16a34a;"></i> Attendance Statistics
                </div>
                <div class="card-body d-flex align-items-center">
                    <canvas id="absensiChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-grid-fill me-2" style="color: #16a34a;"></i> Quick Menu
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <a href="{{ route('karyawan.index') }}" class="text-decoration-none shortcut-card">
                                <div class="icon-bg"><i class="bi bi-people"></i></div>
                                <p class="mt-2 mb-0 fw-semibold text-dark">Employees</p>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('jadwal.index') }}" class="text-decoration-none shortcut-card">
                                <div class="icon-bg"><i class="bi bi-calendar-week"></i></div>
                                <p class="mt-2 mb-0 fw-semibold text-dark">Jadwal</p>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('pola.index') }}" class="text-decoration-none shortcut-card">
                                <div class="icon-bg"><i class="bi bi-clock"></i></div>
                                <p class="mt-2 mb-0 fw-semibold text-dark">Pola Jam</p>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('tipe.index') }}" class="text-decoration-none shortcut-card">
                                <div class="icon-bg"><i class="bi bi-tags"></i></div>
                                <p class="mt-2 mb-0 fw-semibold text-dark">Tipe Shift</p>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="text-decoration-none shortcut-card">
                                <div class="icon-bg"><i class="bi bi-calendar-check"></i></div>
                                <p class="mt-2 mb-0 fw-semibold text-dark">Attendance</p>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="text-decoration-none shortcut-card">
                                <div class="icon-bg"><i class="bi bi-hourglass-split"></i></div>
                                <p class="mt-2 mb-0 fw-semibold text-dark">Overtime</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= LATEST EMPLOYEES ================= --}}
    <div class="card">
        <div class="card-header">
            <i class="bi bi-person-lines-fill me-2" style="color: #16a34a;"></i>
            Latest Registered Employees
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawanTerbaru as $karyawan)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width:40px; height:40px;">
                                            <i class="bi bi-person fs-5 text-secondary"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $karyawan['kar_nama'] }}</span>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $karyawan['kar_email'] }}</td>
                                <td>
                                    <span class="badge-custom">{{ $karyawan['jabatan_kode'] ?? 'N/A' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-emoji-frown fs-3 d-block mb-2"></i>
                                    No recent employees found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('absensiChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($absensiLabels),
                    datasets: [{
                        label: 'Attendance',
                        data: @json($absensiData),
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#22c55e',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#e5e7eb' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endsection
