@extends('layouts.app')

@section('title', 'Lihat Lokasi Kantor')

@section('content')
<div class="container-fluid py-4">

    {{-- Leaflet CSS for Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <style>
        body { background-color: #f0fdf4; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 15px; border: none; animation: fadeInUp 0.5s ease; box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
        .card-header { border-top-left-radius: 15px; border-top-right-radius: 15px; background: linear-gradient(90deg, #16a34a, #22c55e); color: #fff; font-weight: 600; }
        .btn-rounded { border-radius: 50px; transition: all 0.3s ease; }
        .btn-rounded:hover { transform: translateY(-2px); }
        .btn-primary { background-color: #22c55e; border: none; }
        .btn-primary:hover { background-color: #16a34a; }
        .alert { border-radius: 12px; }
        .alert-success { background-color: #dcfce7; color: #166534; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; }
        #map { height: 400px; border-radius: 10px; z-index: 1; }
        .details-grid { display: grid; grid-template-columns: 180px 1fr; gap: 10px; }
        .details-grid dt { font-weight: 600; color: #166534; }
        .details-grid dd { margin-bottom: 0; }
        .badge { font-size: 1rem; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success mb-0">
                <i class="bi bi-eye-fill me-2"></i>Detail Lokasi Kantor
            </h3>
            <small class="text-muted">Tampilan read-only untuk lokasi absensi.</small>
        </div>
        <a href="{{ route('lokasi.edit') }}" class="btn btn-primary shadow-sm btn-rounded">
            <i class="bi bi-pencil-square me-1"></i> Edit Lokasi
        </a>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error') || isset($error) || !$lokasi)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') ?? $error ?? 'Data lokasi belum diatur. Silakan edit untuk menambahkan data.' }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Details Card --}}
    @if($lokasi)
    <div class="card">
        <div class="card-header">
            <i class="bi bi-geo-alt-fill me-2"></i>Lokasi Tersimpan
        </div>
        <div class="card-body px-4 py-4">
            <div class="row">
                <div class="col-md-5">
                    <dl class="details-grid">
                        <dt>Nama Lokasi</dt>
                        <dd>{{ $lokasi->name }}</dd>

                        <dt>Latitude</dt>
                        <dd>{{ $lokasi->latitude }}</dd>

                        <dt>Longitude</dt>
                        <dd>{{ $lokasi->longitude }}</dd>

                        <dt>Radius Absensi</dt>
                        <dd><span class="badge bg-success">{{ $lokasi->radius }} meter</span></dd>
                    </dl>
                </div>
                <div class="col-md-7">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(isset($lokasi) && $lokasi->latitude && $lokasi->longitude)
        const lat = {{ $lokasi->latitude }};
        const lon = {{ $lokasi->longitude }};
        const radius = {{ $lokasi->radius }};

        // Initialize map
        const map = L.map('map').setView([lat, lon], 16);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Add marker for the office
        L.marker([lat, lon]).addTo(map)
            .bindPopup('<b>{{ $lokasi->name }}</b><br>Pusat lokasi absensi.').openPopup();

        // Add circle for the radius
        L.circle([lat, lon], {
            color: 'green',
            fillColor: '#86efac',
            fillOpacity: 0.4,
            radius: radius
        }).addTo(map);
    @endif
});
</script>

@endsection
