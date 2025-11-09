@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<style>
body {
    background-color: #f0fdf4;
    font-family: 'Poppins', sans-serif;
}
.card {
    border-radius: 15px;
    border: none;
    animation: fadeInUp 0.4s ease;
}
.card-header {
    background: linear-gradient(90deg, #16a34a, #22c55e);
    color: white;
    font-weight: 600;
    border-top-left-radius: 15px !important;
    border-top-right-radius: 15px !important;
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
.form-control:focus, .form-select:focus {
    border-color: #22c55e;
    box-shadow: 0 0 0 0.25rem rgba(34,197,94,0.25);
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>

<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Jadwal
            </h3>
            <small class="text-secondary">Perbarui informasi jadwal kerja</small>
        </div>
        <span class="badge bg-success-subtle text-success-emphasis px-3 py-2 rounded-pill shadow-sm">
            <i class="bi bi-tag me-1"></i>Kode: {{ $jadwal->jadwal_kode ?? '-' }}
        </span>
    </div>

    {{-- Alert --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i class="bi bi-calendar-week me-2"></i>Informasi Jadwal
        </div>
        <div class="card-body">
            <form action="{{ route('jadwal.update', $jadwal->jadwal_kode) }}" method="POST" id="jadwalForm">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Kode Jadwal --}}
                    <div class="col-12">
                        <label for="jadwal_kode" class="form-label fw-medium">Kode Jadwal</label>
                        <input type="text" class="form-control bg-light" id="jadwal_kode" name="jadwal_kode"
                            value="{{ $jadwal->jadwal_kode }}" readonly>
                        <small class="text-muted">Kode tidak dapat diubah.</small>
                    </div>

                    {{-- Nama Jadwal --}}
                    <div class="col-12">
                        <label for="jadwal_nama" class="form-label fw-medium">Nama Jadwal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jadwal_nama') is-invalid @enderror"
                            id="jadwal_nama" name="jadwal_nama"
                            value="{{ old('jadwal_nama', $jadwal->jadwal_nama) }}"
                            placeholder="Contoh: Shift Pagi" maxlength="100" required>
                        @error('jadwal_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jam Mulai --}}
                    <div class="col-md-6">
                        <label for="jam_mulai" class="form-label fw-medium">Jam Mulai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai"
                            name="jam_mulai"
                            value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}" required>
                        @error('jam_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="col-md-6">
                        <label for="jam_selesai" class="form-label fw-medium">Jam Selesai <span class="text-danger">*</span></label>
                        <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai"
                            name="jam_selesai"
                            value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}" required>
                        @error('jam_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Durasi Kerja --}}
                    <div class="col-12">
                        <div class="p-3 rounded bg-success-subtle border border-success-subtle">
                            <strong class="text-success-emphasis"><i class="bi bi-stopwatch me-2"></i>Durasi Kerja:</strong>
                            <span id="durasi" class="text-success-emphasis ms-1">-</span>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-12">
                        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input border-success" type="radio" name="status" value="1"
                                    id="aktif" {{ old('status', $jadwal->status) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label text-success" for="aktif">Aktif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input border-success" type="radio" name="status" value="0"
                                    id="nonaktif" {{ old('status', $jadwal->status) == '0' ? 'checked' : '' }}>
                                <label class="form-check-label text-secondary" for="nonaktif">Nonaktif</label>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Tambahan --}}
                    <div class="col-12">
                        <div class="bg-light p-3 rounded border">
                            <h6 class="fw-semibold mb-3 text-muted"><i class="bi bi-info-circle me-2"></i>Informasi Tambahan</h6>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    <span class="text-secondary">Dibuat:</span>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($jadwal->created_at)->format('d M Y H:i') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-secondary">Terakhir diubah:</span>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($jadwal->updated_at)->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="text-end mt-4 border-top pt-3">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary btn-rounded me-2 px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary btn-rounded px-4">
                        <i class="bi bi-arrow-repeat me-1"></i> Perbarui Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
function calculateDuration() {
    const jamMulai = document.getElementById('jam_mulai').value;
    const jamSelesai = document.getElementById('jam_selesai').value;
    const durasiEl = document.getElementById('durasi');

    if (jamMulai && jamSelesai) {
        const start = new Date('2000-01-01 ' + jamMulai);
        let end = new Date('2000-01-01 ' + jamSelesai);

        if (end < start) end.setDate(end.getDate() + 1);

        const diff = (end - start) / 1000 / 60;
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
