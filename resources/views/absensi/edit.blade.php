@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<div class="container-fluid py-4">

    {{-- ==================== CUSTOM STYLES ==================== --}}
    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: 15px;
            border: none;
            animation: fadeInUp 0.5s ease;
        }

        .card-header {
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            color: #fff;
            font-weight: 600;
        }

        .shadow-soft {
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        }

        .btn-rounded {
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-rounded:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: #22c55e;
            border: none;
        }

        .btn-primary:hover {
            background-color: #16a34a;
        }

        .btn-secondary {
            background-color: #9ca3af;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #6b7280;
        }

        .form-label {
            font-weight: 600;
            color: #166534;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(34,197,94,0.25);
            border-color: #22c55e;
        }

        .alert {
            border-radius: 12px;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    {{-- ==================== HEADER ==================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Absensi
            </h3>
            <small class="text-muted">Perbarui data absensi karyawan</small>
        </div>
        <a href="{{ route('absensi.index') }}" class="btn btn-secondary shadow-sm btn-rounded">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    {{-- ==================== ALERT ==================== --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-soft">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Mohon periksa kembali inputan Anda.
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-soft">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ==================== FORM CARD ==================== --}}
    <div class="card shadow-soft">
        <div class="card-header">
            <i class="bi bi-calendar-check-fill me-2"></i>Form Edit Absensi
        </div>

        <div class="card-body p-4">
            @if($absensi)
            <form action="{{ route('absensi.update', $absensi['id']) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- DATA UTAMA --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control"
                               value="{{ $absensi['karyawan']['kar_nama'] ?? 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control"
                               value="{{ \Carbon\Carbon::parse($absensi['tanggal'])->isoFormat('dddd, D MMMM Y') }}"
                               disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Check-in</label>
                        <input type="time" class="form-control"
                               value="{{ \Carbon\Carbon::parse($absensi['check_in'])->format('H:i') }}"
                               disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Check-out</label>
                        <input type="time" class="form-control"
                               value="{{ $absensi['check_out'] ? \Carbon\Carbon::parse($absensi['check_out'])->format('H:i') : '' }}"
                               disabled>
                    </div>
                </div>

                {{-- LOKASI --}}
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude"
                               class="form-control @error('latitude') is-invalid @enderror"
                               value="{{ old('latitude', $absensi['latitude']) }}">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude"
                               class="form-control @error('longitude') is-invalid @enderror"
                               value="{{ old('longitude', $absensi['longitude']) }}">
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="mt-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach(['Hadir','Terlambat','Izin','Sakit','Alpha'] as $s)
                            <option value="{{ $s }}"
                                {{ old('status', $absensi['status']) == $s ? 'selected' : '' }}>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KETERANGAN --}}
                <div class="mt-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $absensi['keterangan']) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ACTION --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('absensi.index') }}" class="btn btn-secondary btn-rounded shadow-sm px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </form>
            @else
                <div class="alert alert-warning">Data absensi tidak ditemukan.</div>
            @endif
        </div>
    </div>
</div>
@endsection
