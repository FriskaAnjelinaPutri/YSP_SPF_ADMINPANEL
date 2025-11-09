@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="container-fluid py-4">

    {{-- ==================== CUSTOM STYLES (Selaras dengan Tambah Karyawan) ==================== --}}
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
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .btn-rounded {
            border-radius: 50px;
            transition: all 0.3s ease;
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

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(34,197,94,0.25);
            border-color: #22c55e;
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

    {{-- Header --}}
    <div class="mb-4">
        <h3 class="fw-bold text-success mb-1">
            <i class="bi bi-calendar-event me-2"></i>Tambah Jadwal Baru
        </h3>
        <p class="text-muted">Buat jadwal kerja dengan jam masuk dan keluar</p>
    </div>

    {{-- Alert Error --}}
    @if (session('error'))
        <div class="alert alert-danger shadow-soft" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Card Form --}}
    <div class="card shadow-soft">
        <div class="card-header">
            <i class="bi bi-calendar-check me-2"></i>Form Tambah Jadwal
        </div>
        <div class="card-body">
            <form action="{{ route('jadwal.store') }}" method="POST" id="jadwalForm">
                @csrf

                {{-- Nama Jadwal --}}
                <div class="mb-3">
                    <label for="jadwal_nama" class="form-label fw-semibold">Nama Jadwal <span class="text-danger">*</span></label>
                    <input type="text" name="jadwal_nama" id="jadwal_nama"
                        value="{{ old('jadwal_nama') }}"
                        class="form-control @error('jadwal_nama') is-invalid @enderror"
                        placeholder="Contoh: Shift Pagi" required maxlength="100">
                    @error('jadwal_nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    {{-- Jam Mulai --}}
                    <div class="col-md-6 mb-3">
                        <label for="jam_mulai" class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_mulai" id="jam_mulai"
                            value="{{ old('jam_mulai') }}"
                            class="form-control @error('jam_mulai') is-invalid @enderror" required>
                        @error('jam_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="col-md-6 mb-3">
                        <label for="jam_selesai" class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" name="jam_selesai" id="jam_selesai"
                            value="{{ old('jam_selesai') }}"
                            class="form-control @error('jam_selesai') is-invalid @enderror" required>
                        @error('jam_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Durasi (Auto Calculate) --}}
                <div class="alert alert-success mt-2 py-2">
                    <i class="bi bi-stopwatch me-2"></i>Durasi kerja: <span id="durasi">-</span>
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="aktif" value="1"
                                {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="nonaktif" value="0"
                                {{ old('status') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="nonaktif">Nonaktif</label>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="text-end mt-4 border-top pt-3">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary btn-rounded me-2 px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary btn-rounded px-4">
                        <i class="bi bi-save me-1"></i> Simpan Jadwal
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

{{-- Script Durasi --}}
@section('scripts')
<script>
    function calculateDuration() {
        const jamMulai = document.getElementById('jam_mulai').value;
        const jamSelesai = document.getElementById('jam_selesai').value;
        const durasiEl = document.getElementById('durasi');

        if (jamMulai && jamSelesai) {
            const start = new Date('2000-01-01T' + jamMulai);
            let end = new Date('2000-01-01T' + jamSelesai);

            if (end < start) end.setDate(end.getDate() + 1);

            const diff = (end - start) / 1000 / 60; // minutes
            const hours = Math.floor(diff / 60);
            const minutes = diff % 60;
            durasiEl.textContent = `${hours} jam ${minutes} menit`;
        } else {
            durasiEl.textContent = '-';
        }
    }

    document.getElementById('jam_mulai').addEventListener('change', calculateDuration);
    document.getElementById('jam_selesai').addEventListener('change', calculateDuration);
    document.addEventListener('DOMContentLoaded', calculateDuration);
</script>
@endsection
@endsection
