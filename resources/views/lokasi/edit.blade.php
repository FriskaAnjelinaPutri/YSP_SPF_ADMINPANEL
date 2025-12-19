@extends('layouts.app')

@section('title', 'Edit Lokasi Kantor')

@section('content')
<div class="container-fluid py-4">

    {{-- Custom Styles --}}
    <style>
        body { background-color: #f0fdf4; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 15px; border: none; animation: fadeInUp 0.5s ease; box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
        .card-header { border-top-left-radius: 15px; border-top-right-radius: 15px; background: linear-gradient(90deg, #16a34a, #22c55e); color: #fff; font-weight: 600; }
        .btn-rounded { border-radius: 50px; transition: all 0.3s ease; }
        .btn-rounded:hover { transform: translateY(-2px); }
        .btn-primary { background-color: #22c55e; border: none; }
        .btn-primary:hover { background-color: #16a34a; }
        .btn-secondary { background-color: #9ca3af; border: none; }
        .btn-secondary:hover { background-color: #6b7280; }
        .form-control:focus, .form-select:focus { box-shadow: 0 0 0 0.25rem rgba(34,197,94,0.25); border-color: #22c55e; }
        .alert { border-radius: 12px; }
        .alert-success { background-color: #dcfce7; color: #166534; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; }
        .map-link { font-size: 0.85rem; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Lokasi Kantor
            </h3>
            <small class="text-muted">Atur ulang koordinat dan radius untuk absensi karyawan.</small>
        </div>
        <a href="{{ route('lokasi.show') }}" class="btn btn-secondary shadow-sm btn-rounded">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Tampilan
        </a>
    </div>

    {{-- Alerts --}}
    @if (session('error') || isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') ?? $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Mohon periksa kembali inputan Anda.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card">
        <div class="card-header">
            <i class="bi bi-pencil-square me-2"></i>Form Edit Lokasi
        </div>

        <div class="card-body px-4 py-4">
            <form action="{{ route('lokasi.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label for="nama" class="form-label fw-semibold">Nama Lokasi</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $lokasi->nama ?? '') }}" required>
                        <small class="text-muted">Contoh: Kantor Pusat, Pabrik Indarung</small>
                    </div>

                    <div class="col-md-6">
                        <label for="latitude" class="form-label fw-semibold">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $lokasi->latitude ?? '') }}" required>
                        <small class="text-muted">Koordinat Lintang. Contoh: -0.949666</small>
                    </div>

                    <div class="col-md-6">
                        <label for="longitude" class="form-label fw-semibold">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $lokasi->longitude ?? '') }}" required>
                        <small class="text-muted">Koordinat Bujur. Contoh: 100.354165</small>
                    </div>

                    <div class="col-md-6">
                        <label for="radius" class="form-label fw-semibold">Radius (meter)</label>
                        <input type="number" class="form-control" id="radius" name="radius" value="{{ old('radius', $lokasi->radius ?? '') }}" required>
                        <small class="text-muted">Jarak toleransi absensi dalam satuan meter.</small>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
