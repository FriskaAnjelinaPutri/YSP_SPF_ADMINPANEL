@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- Greeting --}}
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

        <h4 class="fw-bold">{{ $greeting }}, {{ $adminName }} 👋</h4>
        <p class="text-muted">Here’s a quick overview of your system today.</p>
    </div>

    {{-- Shortcut Menu --}}
    <div class="row g-3 mb-4 text-center">
        <div class="col-6 col-md-3">
            <a href="{{ route('karyawan.index') }}" class="card shadow-sm border-0 text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <h6 class="fw-semibold">Employees</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="#" class="card shadow-sm border-0 text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-calendar-check fs-3"></i>
                    </div>
                    <h6 class="fw-semibold">Attendance</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="#" class="card shadow-sm border-0 text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-warning text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <h6 class="fw-semibold">Overtime</h6>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="#" class="card shadow-sm border-0 text-decoration-none text-dark h-100">
                <div class="card-body p-4">
                    <div class="bg-danger text-white rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-calendar-event fs-3"></i>
                    </div>
                    <h6 class="fw-semibold">Leave</h6>
                </div>
            </a>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow h-100 rounded-4">
                <div class="card-body">
                    <h6>Total Employees</h6>
                    <h2 class="fw-bold">{{ $totalKaryawan ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow h-100 rounded-4">
                <div class="card-body">
                    <h6>Today’s Attendance</h6>
                    <h2 class="fw-bold">{{ $absensiHariIni ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow h-100 rounded-4">
                <div class="card-body">
                    <h6>Overtime This Month</h6>
                    <h2 class="fw-bold">{{ $totalLembur ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow h-100 rounded-4">
                <div class="card-body">
                    <h6>Leave Requests</h6>
                    <h2 class="fw-bold">{{ $totalCuti ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white fw-semibold">Attendance Statistics</div>
                <div class="card-body">
                    <canvas id="absensiChart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white fw-semibold">Overtime Statistics</div>
                <div class="card-body">
                    <canvas id="lemburChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Employees --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white fw-semibold">Latest Employees</div>
        <div class="card-body">
            @forelse($karyawanTerbaru ?? [] as $karyawan)
                <div class="d-flex align-items-center border-bottom py-2">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width:45px; height:45px;">
                        <i class="bi bi-person fs-4 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $karyawan['kar_nama'] }}</h6>
                        <small class="text-muted">{{ $karyawan['kar_email'] }}</small><br>
                        <span class="badge bg-primary">{{ $karyawan['jabatan_kode'] }}</span>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center mb-0">No data available</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const absensiChart = new Chart(document.getElementById('absensiChart'), {
        type: 'line',
        data: {
            labels: @json($absensiLabels ?? []),
            datasets: [{
                label: 'Attendance',
                data: @json($absensiData ?? []),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                fill: true,
                tension: 0.4
            }]
        }
    });

    const lemburChart = new Chart(document.getElementById('lemburChart'), {
        type: 'bar',
        data: {
            labels: @json($lemburLabels ?? []),
            datasets: [{
                label: 'Overtime',
                data: @json($lemburData ?? []),
                backgroundColor: '#ffc107'
            }]
        }
    });
</script>
@endsection
