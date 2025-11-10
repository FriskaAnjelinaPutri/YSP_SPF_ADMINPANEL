@extends('layouts.app')

@section('title', 'Detail Absensi')

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
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }
    .card-header {
        border-top-left-radius: 20px !important;
        border-top-right-radius: 20px !important;
        background: linear-gradient(90deg, #16a34a, #22c55e);
        color: #fff;
    }

    /* === Table Detail === */
    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }
    .detail-table th {
        width: 200px;
        font-weight: 600;
        color: #14532d;
        background-color: #dcfce7;
        border: none;
    }
    .detail-table td {
        background-color: #fff;
        border: none;
        padding: 10px 12px;
        color: #374151;
    }
    .detail-table tr:nth-child(even) td {
        background-color: #f9fafb;
    }
    .detail-table tr:hover td {
        background-color: rgba(22,101,52,0.05);
    }

    /* === Map Container === */
    .map-container {
        height: 300px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

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
    .badge-secondary { background: #6b7280; color: #fff; }

    /* === Buttons === */
    .btn-rounded {
        border-radius: 50px;
        transition: transform 0.2s ease;
    }
    .btn-rounded:hover { transform: scale(1.05); }

    .btn-outline-secondary {
        border-color: #166534;
        color: #166534;
    }
    .btn-outline-secondary:hover {
        background-color: #16a34a;
        color: #fff;
    }

    /* === Alerts === */
    .alert {
        border-radius: 15px;
        padding: 12px 18px;
        font-size: 0.9rem;
    }
    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .alert-warning {
        background-color: #fef9c3;
        color: #854d0e;
    }

    /* === Responsive === */
    @media (max-width: 768px) {
        .detail-table th {
            width: 150px;
            font-size: 0.9rem;
        }
        .detail-table td {
            font-size: 0.9rem;
        }
    }
    </style>


    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-file-person-fill me-2"></i>Detail Absensi
            </h3>
            <small class="text-secondary">Rincian lengkap data absensi</small>
        </div>
        <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary btn-rounded">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Alerts --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Detail Card --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">Informasi Absensi</h5>
        </div>
        <div class="card-body p-4">
            @if($absensi)
                <div class="row">
                    <div class="col-lg-7">
                        <table class="table table-bordered detail-table">
                            <tbody>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <td>{{ $absensi['karyawan']['kar_nama'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($absensi['tanggal'])->isoFormat('dddd, D MMMM Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Check-in</th>
                                    <td>{{ \Carbon\Carbon::parse($absensi['check_in'])->format('H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Check-out</th>
                                    <td>{{ $absensi['check_out'] ? \Carbon\Carbon::parse($absensi['check_out'])->format('H:i:s') : 'Belum Check-out' }}</td>
                                </tr>
                                <tr>
                                    <th>Durasi Kerja</th>
                                    <td>{{ $durasi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @php
                                            $status_badge = [
                                                'Hadir' => 'badge-success',
                                                'Terlambat' => 'badge-warning',
                                                'Izin' => 'badge-info',
                                                'Sakit' => 'badge-danger',
                                                'Alpha' => 'badge-secondary',
                                            ];
                                        @endphp
                                        <span class="badge {{ $status_badge[$absensi['status']] ?? 'badge-light' }}">{{ $absensi['status'] }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $absensi['keterangan'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td>Lat: {{ $absensi['latitude'] }}, Long: {{ $absensi['longitude'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-5">
                        <div id="map" class="map-container"></div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">Data absensi tidak ditemukan.</div>
            @endif
        </div>
    </div>
</div>

{{-- LeafletJS for Map --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($absensi && $absensi['latitude'] && $absensi['longitude'])
            var lat = {{ $absensi['latitude'] }};
            var lon = {{ $absensi['longitude'] }};

            var map = L.map('map').setView([lat, lon], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([lat, lon]).addTo(map)
                .bindPopup('Lokasi Absen <br> {{ $absensi['karyawan']['kar_nama'] ?? '' }}')
                .openPopup();
        @endif
    });
</script>
@endsection
