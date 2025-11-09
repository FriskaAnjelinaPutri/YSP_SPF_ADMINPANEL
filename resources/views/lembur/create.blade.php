@extends('layouts.app')

@section('title', 'Tambah Lembur')

@section('content')
<div class="container-fluid py-4">

    {{-- Custom Styles --}}
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

/* === Radio Buttons (Status) === */
.status-radio {
    width: 20px;
    height: 20px;
    cursor: pointer;
    transition: all 0.25s ease-in-out;
    border: 2px solid #d1d5db;
}
.status-radio:checked {
    transform: scale(1.15);
    box-shadow: 0 0 0 3px rgba(34,197,94,0.25);
}
#approved:checked {
    background-color: #16a34a;
    border-color: #16a34a;
}
#pending:checked {
    background-color: #facc15;
    border-color: #facc15;
}
#rejected:checked {
    background-color: #dc2626;
    border-color: #dc2626;
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
                <i class="bi bi-stopwatch-fill me-2"></i>Tambah Data Lembur
            </h3>
            <small class="text-muted">Isi formulir untuk pengajuan lembur baru</small>
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
            <i class="bi bi-pencil-square me-2"></i>Form Pengajuan Lembur
        </div>
        <div class="card-body px-4 py-4">
            <form action="{{ route('lembur.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    {{-- Karyawan --}}
                    <div class="col-md-6">
                        <label for="karyawan_id" class="form-label">Karyawan</label>
                        <select name="karyawan_id" id="karyawan_id" class="form-select" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach ($karyawans as $karyawan)
                                <option value="{{ $karyawan['kar_kode'] }}" {{ old('karyawan_id') == $karyawan['kar_kode'] ? 'selected' : '' }}>
                                    {{ $karyawan['kar_nama'] }} ({{ $karyawan['kar_nip'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                    </div>

                    {{-- Jam Mulai --}}
                    <div class="col-md-6">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="col-md-6">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required>
                    </div>

                    {{-- Alasan --}}
                    <div class="col-12">
                        <label for="alasan" class="form-label">Alasan Lembur</label>
                        <textarea name="alasan" id="alasan" class="form-control" rows="3" required>{{ old('alasan') }}</textarea>
                    </div>

                    {{-- Status (Radio Buttons) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input border-warning status-radio" type="radio" name="status"
                                    id="pending" value="Pending" {{ old('status') == 'Pending' ? 'checked' : '' }}>
                                <label class="form-check-label text-warning fw-semibold" for="pending">
                                    Pending
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input border-success status-radio" type="radio" name="status"
                                    id="approved" value="Approved" {{ old('status') == 'Approved' ? 'checked' : '' }}>
                                <label class="form-check-label text-success fw-semibold" for="approved">
                                    Approved
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input border-danger status-radio" type="radio" name="status"
                                    id="rejected" value="Rejected" {{ old('status') == 'Rejected' ? 'checked' : '' }}>
                                <label class="form-check-label text-danger fw-semibold" for="rejected">
                                    Rejected
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan') }}">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan
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
