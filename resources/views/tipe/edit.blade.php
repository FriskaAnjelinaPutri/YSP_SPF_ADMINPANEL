@extends('layouts.app')

@section('title', 'Edit Tipe')

@section('content')
<div class="container-fluid">

<style>
/* === General === */
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
.card:hover { transform: translateY(-3px); }
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
            <i class="bi bi-pencil-square me-2"></i>Edit Tipe
        </h3>
        <small class="text-secondary">Perbarui informasi tipe kerja</small>
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
        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Form Edit Tipe</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('tipe.update', $tipe['tipe_kode']) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Kode Tipe --}}
            <div class="mb-4">
                <label for="tipe_kode" class="form-label">Kode Tipe</label>
                <input type="text" id="tipe_kode" name="tipe_kode"
                       class="form-control bg-light"
                       value="{{ $tipe['tipe_kode'] }}" readonly>
                <small class="text-secondary">Kode tidak dapat diubah</small>
            </div>

            {{-- Nama Tipe --}}
            <div class="mb-4">
                <label for="tipe_nama" class="form-label">Nama Tipe <span class="text-danger">*</span></label>
                <input type="text" id="tipe_nama" name="tipe_nama"
                       class="form-control @error('tipe_nama') is-invalid @enderror"
                       value="{{ old('tipe_nama', $tipe['tipe_nama']) }}"
                       placeholder="Contoh: Shift Pagi" maxlength="100" required>
                @error('tipe_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-secondary">Maksimal 100 karakter</small>
            </div>

            {{-- Status Aktif --}}
            <div class="mb-4">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 mt-2">
                    <div class="form-check">
                        <input class="form-check-input border-success" type="radio" name="tipe_aktif" value="1"
                            id="aktif" {{ old('tipe_aktif', $tipe['tipe_aktif']) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label text-success fw-medium" for="aktif">Aktif</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input border-secondary" type="radio" name="tipe_aktif" value="0"
                            id="nonaktif" {{ old('tipe_aktif', $tipe['tipe_aktif']) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label text-secondary fw-medium" for="nonaktif">Nonaktif</label>
                    </div>
                </div>
            </div>

            {{-- Informasi Tambahan --}}
            <div class="mb-4 p-3 bg-gray-50 rounded-3 border">
                <h6 class="text-primary fw-semibold mb-2">Informasi Tambahan</h6>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-secondary d-block">Dibuat:</small>
                        <span class="fw-medium">
                            {{ \Carbon\Carbon::parse($tipe['created_at'])->format('d M Y H:i') }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-secondary d-block">Terakhir Diubah:</small>
                        <span class="fw-medium">
                            {{ \Carbon\Carbon::parse($tipe['updated_at'])->format('d M Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('tipe.index') }}" class="btn btn-secondary btn-rounded">
                    <i class="bi bi-arrow-left-circle me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary btn-rounded">
                    <i class="bi bi-save2 me-1"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection
