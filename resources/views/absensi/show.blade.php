@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<div class="container-fluid">

    {{-- Custom Styles --}}
    <style>
        body { background-color: #f0fdf4; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 20px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card-header { background: linear-gradient(90deg, #3b82f6, #2563eb); color: #fff; border-top-left-radius: 20px; border-top-right-radius: 20px; }
        .detail-table th { width: 200px; font-weight: 600; color: #166534; background-color: #f0fdf4; }
        .map-container { height: 300px; border-radius: 15px; }
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
