@extends('layouts.app')

@section('title', 'Data Absensi')

@section('content')
<div class="container-fluid">

    {{-- Custom Styles --}}
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

    /* === Table === */
    .table-hover tbody tr:hover {
        background-color: rgba(22,101,52,0.08);
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    .table-hover tr.active-row {
        background-color: #dcfce7 !important;
        transition: background-color 0.3s ease;
    }
    .table th, .table td {
        vertical-align: middle;
        padding: 12px 15px;
    }
    .table thead {
        background-color: #dcfce7;
    }
    .table td strong { font-size: 0.95rem; color: #14532d; }
    .table td small { font-size: 0.75rem; color: #6b7280; }

    /* === Badges === */
    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.8em;
        border-radius: 12px;
        font-weight: 500;
        text-transform: capitalize;
    }
    .badge-success { background: #22c55e; color: #fff; }
    .badge-warning { background: #facc15; color: #000; }
    .badge-info { background: #0ea5e9; color: #fff; }
    .badge-danger { background: #dc2626; color: #fff; }
    .badge-dark { background: #6b7280; color: #fff; }

    /* === Summary Cards === */
    .summary-card {
        background-color: #fff;
        border-radius: 15px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .summary-card .icon { font-size: 2rem; }
    .summary-card h5 { font-weight: 600; }

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

    .btn-outline-warning {
        border-color: #facc15;
        color: #ca8a04;
    }
    .btn-outline-warning:hover {
        background-color: #facc15;
        color: black;
    }
    .btn-outline-danger {
        border-color: #dc2626;
        color: #dc2626;
    }
    .btn-outline-danger:hover {
        background-color: #dc2626;
        color: white;
    }
    .btn-outline-info {
        border-color: #0ea5e9;
        color: #0ea5e9;
    }
    .btn-outline-info:hover {
        background-color: #0ea5e9;
        color: #fff;
    }

    /* === Alerts === */
    .alert {
        border-radius: 15px;
        padding: 12px 18px;
        font-size: 0.9rem;
    }
    .alert-success {
        background-color: #dcfce7;
        color: #166534;
    }
    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* === Responsive === */
    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 0.85rem;
        }
    }
    @media (max-width: 576px) {
        table th:nth-child(3),
        table td:nth-child(3),
        table th:nth-child(4),
        table td:nth-child(4) {
            display: none;
        }
    }
    </style>


    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-calendar-check-fill me-2"></i>Data Absensi
            </h3>
            <small class="text-secondary">Manajemen data absensi karyawan</small>
        </div>

    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error') || isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') ?? $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row mb-4">
        @php
            $summary_items = [
                'Hadir' => ['icon' => 'bi-person-check-fill', 'color' => 'text-success'],
                'Terlambat' => ['icon' => 'bi-clock-history', 'color' => 'text-warning'],
                'Izin' => ['icon' => 'bi-envelope-fill', 'color' => 'text-info'],
                'Sakit' => ['icon' => 'bi-bandaid-fill', 'color' => 'text-danger'],
                'Alpha' => ['icon' => 'bi-person-x-fill', 'color' => 'text-secondary'],
            ];
        @endphp
        @foreach($summary_items as $status => $item)
            <div class="col">
                <div class="summary-card">
                    <div class="icon {{ $item['color'] }}"><i class="bi {{ $item['icon'] }}"></i></div>
                    <h5 class="mt-2 mb-0">{{ $summary['total_'.strtolower($status)] ?? 0 }}</h5>
                    <small class="text-muted">{{ $status }}</small>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Card Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2"></i>Daftar Absensi
            </h5>
            <div class="d-flex align-items-center">
                <a href="{{ route('lokasi.show') }}" class="btn btn-info btn-sm btn-rounded me-3">
                    <i class="bi bi-geo-alt-fill me-1"></i> Pengaturan Lokasi
                </a>
                <form method="GET" action="{{ route('absensi.index') }}" class="d-flex align-items-center">
                    <input type="text" name="kar_nama" class="form-control form-control-sm me-2" style="width: 200px;" placeholder="Cari Nama Karyawan..." value="{{ $kar_nama ?? '' }}">
                    <select name="status" class="form-select form-select-sm me-2" style="width: 150px;">
                        <option value="">Semua Status</option>
                        @foreach(['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha'] as $s)
                            <option value="{{ $s }}" {{ ($status ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    <input type="month" name="periode" class="form-control form-control-sm me-2" value="{{ $periode }}">
                    <button type="submit" class="btn btn-light btn-sm"><i class="bi bi-filter"></i> Filter</button>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Tanggal</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $absen)
                        <tr>
                            <td>
                                <strong>{{ $absen['karyawan']['kar_nama'] ?? 'N/A' }}</strong><br>
                                <small>{{ $absen['karyawan']['jabatan']['jabatan_nama'] ?? '' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($absen['tanggal'])->isoFormat('dddd, D MMMM Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ \Carbon\Carbon::parse($absen['check_in'])->format('H:i') }}</span>
                            </td>
                            <td>
                                @if($absen['check_out'])
                                <span class="badge bg-light text-dark">{{ \Carbon\Carbon::parse($absen['check_out'])->format('H:i') }}</span>
                                @else
                                <span class="badge bg-secondary text-white">Belum Checkout</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $status_badge = [
                                        'Hadir' => 'badge-success',
                                        'Terlambat' => 'badge-warning',
                                        'Izin' => 'badge-info',
                                        'Sakit' => 'badge-danger',
                                        'Alpha' => 'badge-dark',
                                    ];
                                @endphp
                                <span class="badge {{ $status_badge[$absen['status']] ?? 'badge-light' }}">{{ $absen['status'] }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('absensi.show', $absen['id']) }}" class="btn btn-sm btn-outline-info me-1 btn-rounded" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('absensi.edit', $absen['id']) }}" class="btn btn-sm btn-outline-warning me-1 btn-rounded" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('absensi.destroy', $absen['id']) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data absensi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-rounded" title="Hapus">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2 fs-5"></i>Belum ada data absensi untuk filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($meta && $meta['last_page'] > 1)
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-secondary small">
                    Menampilkan {{ $meta['from'] }} - {{ $meta['to'] }} dari {{ $meta['total'] }} data
                </div>
                <nav>
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        <li class="page-item {{ !$links['prev'] ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $links['prev'] ? $links['prev'] . '&' . http_build_query(request()->except('page')) : '#' }}">«</a>
                        </li>

                        {{-- Pagination Elements --}}
                        @foreach ($meta['links'] as $link)
                            @if (is_numeric($link['label']))
                                <li class="page-item {{ $link['active'] ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $link['url'] . '&' . http_build_query(request()->except('page')) }}">{{ $link['label'] }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        <li class="page-item {{ !$links['next'] ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $links['next'] ? $links['next'] . '&' . http_build_query(request()->except('page')) : '#' }}">»</a>
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
