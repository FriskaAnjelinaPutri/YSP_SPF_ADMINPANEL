@extends('layouts.app')

@section('title', 'Tambah Tipe')

@section('content')
<div class="container-fluid">

<style>
/* === General Style === */
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
    border: none;
    background: #fff;
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-3px);
}
.card-header {
    background: linear-gradient(90deg, #16a34a, #22c55e);
    color: #fff;
    border-top-left-radius: 20px !important;
    border-top-right-radius: 20px !important;
}

/* === Buttons === */
.btn-rounded {
    border-radius: 50px;
    transition: transform 0.2s ease;
}
.btn-rounded:hover { transform: scale(1.05); }

.btn-primary {
    background-color: #22c55e;
    border: none;
}
.btn-primary:hover { background-color: #16a34a; }

.btn-secondary {
    background-color: #6b7280;
    border: none;
}
.btn-secondary:hover { background-color: #4b5563; }

/* === Form === */
.form-label {
    font-weight: 500;
    color: #166534;
}
.form-select, .form-control {
    border-radius: 12px;
    border: 1px solid #d1d5db;
    box-shadow: none;
}
.form-select:focus, .form-control:focus {
    border-color: #22c55e;
    box-shadow: 0 0 0 0.15rem rgba(34,197,94,0.25);
}

/* === Alert === */
.alert {
    border-radius: 15px;
    padding: 10px 18px;
    font-size: 0.9rem;
}
.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
}
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-plus-circle me-2"></i>Tambah Tipe Baru
        </h3>
        <small class="text-secondary">Masukkan data tipe baru yang akan digunakan pada pola kerja</small>
    </div>
    <a href="{{ route('tipe.index') }}" class="btn btn-secondary btn-rounded shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

{{-- Alert Error --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Form Card --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Form Tambah Tipe</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('tipe.store') }}" method="POST">
            @csrf

            {{-- Info Auto Generate --}}
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                <div class="d-flex align-items-start">
                    <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                    <p class="text-sm text-primary mb-0">
                        <strong>Kode tipe</strong> akan digenerate otomatis oleh sistem setelah data disimpan.
                    </p>
                </div>
            </div>

            {{-- Nama Tipe --}}
            <div class="mb-4">
                <label for="tipe_nama" class="form-label">Nama Tipe <span class="text-danger">*</span></label>
                <input type="text" name="tipe_nama" id="tipe_nama"
                       class="form-control @error('tipe_nama') is-invalid @enderror"
                       value="{{ old('tipe_nama') }}"
                       placeholder="Contoh: Shift Pagi"
                       maxlength="100"
                       required>
                @error('tipe_nama')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <small class="text-secondary">Maksimal 100 karakter</small>
            </div>

            {{-- Status Aktif --}}
            <div class="mb-4">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <div class="d-flex align-items-center gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipe_aktif" id="aktif" value="1"
                               {{ old('tipe_aktif', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipe_aktif" id="nonaktif" value="0"
                               {{ old('tipe_aktif') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="nonaktif">Nonaktif</label>
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('tipe.index') }}" class="btn btn-secondary btn-rounded">
                    <i class="bi bi-arrow-left-circle me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary btn-rounded">
                    <i class="bi bi-save2 me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection
