@extends('layouts.app')

@section('title', 'Edit Lembur')

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
            font-size: 1rem;
        }

        .shadow-soft {
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        }

        .form-label {
            font-weight: 600;
            color: #166534;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(34,197,94,0.25);
            border-color: #22c55e;
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

        .alert {
            border-radius: 12px;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
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
                <i class="bi bi-stopwatch-fill me-2"></i>Edit Data Lembur
            </h3>
            <small class="text-muted">Perbarui data pengajuan lembur</small>
        </div>
        <a href="{{ route('lembur.index') }}" class="btn btn-secondary shadow-sm btn-rounded">
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

    {{-- ==================== FORM CARD ==================== --}}
    <div class="card shadow-soft">
        <div class="card-header">
            <i class="bi bi-pencil-square me-2"></i>Form Edit Lembur
        </div>

        <div class="card-body px-4 py-4">
            <form action="{{ route('lembur.update', $lembur['id']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Karyawan --}}
                    <div class="col-12">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control"
                               value="{{ $lembur['karyawan']['kar_nama'] ?? 'Nama tidak ditemukan' }}" disabled>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control"
                               value="{{ old('tanggal', \Carbon\Carbon::parse($lembur['tanggal'])->format('Y-m-d')) }}" required>
                    </div>

                    {{-- Jam Mulai --}}
                    <div class="col-md-4">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control"
                               value="{{ old('jam_mulai', \Carbon\Carbon::parse($lembur['jam_mulai'])->format('H:i')) }}" required>
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="col-md-4">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control"
                               value="{{ old('jam_selesai', \Carbon\Carbon::parse($lembur['jam_selesai'])->format('H:i')) }}" required>
                    </div>

                    {{-- Alasan --}}
                    <div class="col-12">
                        <label class="form-label">Alasan Lembur</label>
                        <textarea name="alasan" class="form-control" rows="3" required>{{ old('alasan', $lembur['alasan']) }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Pending" {{ old('status', $lembur['status']) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ old('status', $lembur['status']) == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ old('status', $lembur['status']) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-6">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" class="form-control"
                               value="{{ old('keterangan', $lembur['keterangan']) }}">
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('lembur.index') }}" class="btn btn-secondary btn-rounded shadow-sm px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
