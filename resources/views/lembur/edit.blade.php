@extends('layouts.app')

@section('title', 'Edit Lembur')

@section('content')
<div class="container-fluid py-4">

    {{-- Custom Styles --}}
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }
        .card-header {
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            background-color: #118ab2 !important;
        }
        .btn-rounded {
            border-radius: 50px;
        }
        label.form-label {
            color: #2b2d42;
            font-weight: 600;
        }
        .form-control:disabled {
            background-color: #e9ecef;
            opacity: 1;
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-stopwatch-fill me-2"></i>Edit Data Lembur
            </h3>
            <small class="text-muted">Perbarui data pengajuan lembur</small>
        </div>
        <a href="{{ route('lembur.index') }}" class="btn btn-secondary shadow-sm btn-rounded">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Mohon periksa kembali inputan Anda.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card">
        <div class="card-header text-white fw-semibold">
            <i class="bi bi-pencil-square me-2"></i>Form Edit Lembur
        </div>
        <div class="card-body px-4 py-4">
            <form action="{{ route('lembur.update', $lembur->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    {{-- Karyawan (disabled) --}}
                    <div class="col-12">
                        <label for="karyawan_nama" class="form-label">Karyawan</label>
                        <input type="text" id="karyawan_nama" class="form-control"
                               value="{{ $lembur['karyawan']['kar_nama'] ?? 'Nama tidak ditemukan' }}" disabled>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                               value="{{ old('tanggal', \Carbon\Carbon::parse($lembur['tanggal'])->format('Y-m-d')) }}" required>
                    </div>

                    {{-- Jam Mulai --}}
                    <div class="col-md-4">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control"
                               value="{{ old('jam_mulai', \Carbon\Carbon::parse($lembur['jam_mulai'])->format('H:i')) }}" required>
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="col-md-4">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control"
                               value="{{ old('jam_selesai', \Carbon\Carbon::parse($lembur['jam_selesai'])->format('H:i')) }}" required>
                    </div>

                    {{-- Alasan --}}
                    <div class="col-12">
                        <label for="alasan" class="form-label">Alasan Lembur</label>
                        <textarea name="alasan" id="alasan" class="form-control" rows="3" required>{{ old('alasan', $lembur['alasan']) }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Pending" {{ old('status', $lembur['status']) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ old('status', $lembur['status']) == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ old('status', $lembur['status']) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan', $lembur['keterangan']) }}">
                    </div>
                </div>

                {{-- Action Buttons --}}
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
