@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<div class="container-fluid">

    {{-- Custom Styles --}}
    <style>
        body { background-color: #f0fdf4; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 20px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card-header { background: linear-gradient(90deg, #fbbf24, #f59e0b); color: #fff; border-top-left-radius: 20px; border-top-right-radius: 20px; }
        .form-label { font-weight: 600; color: #166534; }
        .btn-warning { background-color: #f59e0b; border-color: #f59e0b; }
        .btn-warning:hover { background-color: #d97706; border-color: #d97706; }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Absensi
            </h3>
            <small class="text-secondary">Perbarui data absensi karyawan</small>
        </div>
        <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary btn-rounded">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> Terdapat kesalahan pada input Anda.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Edit Form Card --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 fw-semibold">Form Edit Absensi</h5>
        </div>
        <div class="card-body p-4">
            @if($absensi)
            <form action="{{ route('absensi.update', $absensi['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control" value="{{ $absensi['karyawan']['kar_nama'] ?? 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($absensi['tanggal'])->isoFormat('dddd, D MMMM Y') }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="check_in" class="form-label">Check-in</label>
                        <input type="time" class="form-control @error('check_in') is-invalid @enderror" id="check_in" name="check_in" value="{{ old('check_in', \Carbon\Carbon::parse($absensi['check_in'])->format('H:i')) }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="check_out" class="form-label">Check-out</label>
                        <input type="time" class="form-control @error('check_out') is-invalid @enderror" id="check_out" name="check_out" value="{{ old('check_out', $absensi['check_out'] ? \Carbon\Carbon::parse($absensi['check_out'])->format('H:i') : '') }}" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $absensi['latitude']) }}" required>
                         @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $absensi['longitude']) }}" required>
                         @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        @foreach(['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha'] as $s)
                            <option value="{{ $s }}" {{ old('status', $absensi['status']) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $absensi['keterangan']) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-warning btn-rounded shadow-sm">
                        <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
            @else
                <div class="alert alert-warning">Data absensi tidak ditemukan.</div>
            @endif
        </div>
    </div>
</div>
@endsection
