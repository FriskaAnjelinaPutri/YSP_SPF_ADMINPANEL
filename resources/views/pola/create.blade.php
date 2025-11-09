@extends('layouts.app')

@section('title', 'Tambah Pola')

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
            <i class="bi bi-plus-circle me-2"></i>Tambah Pola Kerja
        </h3>
        <small class="text-secondary">Isi data pola kerja baru berdasarkan tipe dan jadwal</small>
    </div>
    <a href="{{ route('pola.index') }}" class="btn btn-secondary btn-rounded shadow-sm">
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
        <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Form Tambah Pola</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('pola.store') }}" method="POST">
            @csrf

            {{-- Pilih Tipe --}}
            <div class="mb-4">
                <label for="tipe_kode" class="form-label">Tipe <span class="text-danger">*</span></label>
                <select name="tipe_kode" id="tipe_kode" class="form-select" required>
                    <option value="">-- Pilih Tipe --</option>
                    @foreach ($tipes as $tipe)
                        <option value="{{ $tipe['tipe_kode'] }}">{{ $tipe['tipe_nama'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pilih Jadwal --}}
            <div class="mb-4">
                <label for="jadwal_kode" class="form-label">Jadwal <span class="text-danger">*</span></label>
                <select name="jadwal_kode" id="jadwal_kode" class="form-select" required>
                    <option value="">-- Pilih Jadwal --</option>
                    @foreach ($jadwals as $jadwal)
                        <option value="{{ $jadwal['jadwal_kode'] }}">
                            {{ $jadwal['jadwal_nama'] ?? $jadwal['jadwal_kode'] }}
                            ({{ $jadwal['jam_mulai'] }} - {{ $jadwal['jam_selesai'] }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Urutan --}}
            <div class="mb-4">
                <label for="urut" class="form-label">Urutan <span class="text-danger">*</span></label>
                <input type="number" name="urut" id="urut" class="form-control" placeholder="Masukkan urutan" required>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('pola.index') }}" class="btn btn-secondary btn-rounded">
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
