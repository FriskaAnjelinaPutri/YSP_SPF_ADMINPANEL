@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<div class="container-fluid">

<style>
/* === General Style === */
body {
    background-color: #f4f6f9;
    font-family: 'Poppins', sans-serif;
}
h3, h5 { font-weight: 600; }
.text-primary { color: #118ab2 !important; }

/* === Card === */
.card {
    border-radius: 20px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}
.card-header {
    border-top-left-radius: 20px !important;
    border-top-right-radius: 20px !important;
    background: linear-gradient(90deg, #118ab2, #06d6a0);
    color: #fff;
}

/* === Buttons === */
.btn-rounded {
    border-radius: 50px;
    transition: all 0.2s ease;
}
.btn-rounded:hover { transform: scale(1.05); }

/* === Form === */
.form-control, .form-select {
    border-radius: 12px;
    padding: 10px 14px;
}
label {
    font-weight: 500;
    color: #333;
}

/* === Alerts === */
.alert {
    border-radius: 12px;
    padding: 12px 18px;
    font-size: 0.9rem;
}
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-pencil-square me-2"></i>Edit Jadwal
        </h3>
        <small class="text-secondary">Ubah data jadwal kerja yang sudah ada</small>
    </div>
    <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary btn-rounded">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

{{-- Card Form --}}
<div class="card border-0">
    <div class="card-header">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-calendar-check me-2"></i>Form Edit Jadwal
        </h5>
    </div>

    <div class="card-body">
        <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="jadwal_nama" class="form-label">Nama Jadwal</label>
                <input type="text" id="jadwal_nama" name="jadwal_nama"
                       value="{{ old('jadwal_nama', $jadwal->jadwal_nama) }}"
                       class="form-control @error('jadwal_nama') is-invalid @enderror"
                       placeholder="Contoh: Shift Siang" required>
                @error('jadwal_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" id="jam_mulai" name="jam_mulai"
                           value="{{ old('jam_mulai', $jadwal->jam_mulai) }}"
                           class="form-control @error('jam_mulai') is-invalid @enderror" required>
                    @error('jam_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <input type="time" id="jam_selesai" name="jam_selesai"
                           value="{{ old('jam_selesai', $jadwal->jam_selesai) }}"
                           class="form-control @error('jam_selesai') is-invalid @enderror" required>
                    @error('jam_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status Jadwal</label>
                <select name="status" id="status" class="form-select">
                    <option value="1" {{ $jadwal->status == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $jadwal->status == 0 ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success btn-rounded px-4">
                    <i class="bi bi-save me-1"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection
