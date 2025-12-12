@extends('layouts.app')

@section('title', 'Edit Tiket Helpdesk')

@section('content')
<div class="container-fluid py-4">
    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 15px;
            border: none;
        }
        .card-header {
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            color: #fff;
            font-weight: 600;
        }
        .btn-rounded {
            border-radius: 50px;
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
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Tiket Helpdesk
            </h3>
            <small class="text-muted">Perbarui data tiket helpdesk</small>
        </div>
        <a href="{{ route('helpdesk.index') }}" class="btn btn-secondary shadow-sm btn-rounded">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-soft">
        <div class="card-header">
            <i class="bi bi-headset me-2"></i>Form Edit Tiket
        </div>
        <div class="card-body px-4 py-4">
            <form action="{{ route('helpdesk.update', $helpdesk['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label for="judul" class="form-label fw-semibold">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $helpdesk['judul']) }}" required>
                    </div>
                    <div class="col-12">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $helpdesk['deskripsi']) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Baru" {{ old('status', $helpdesk['status']) == 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Dalam Pengerjaan" {{ old('status', $helpdesk['status']) == 'Dalam Pengerjaan' ? 'selected' : '' }}>Dalam Pengerjaan</option>
                            <option value="Selesai" {{ old('status', $helpdesk['status']) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="prioritas" class="form-label fw-semibold">Prioritas</label>
                        <select class="form-select" id="prioritas" name="prioritas" required>
                            <option value="Rendah" {{ old('prioritas', $helpdesk['prioritas']) == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="Sedang" {{ old('prioritas', $helpdesk['prioritas']) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Tinggi" {{ old('prioritas', $helpdesk['prioritas']) == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="kategori" class="form-label fw-semibold">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="Teknis" {{ old('kategori', $helpdesk['kategori']) == 'Teknis' ? 'selected' : '' }}>Teknis</option>
                            <option value="Aplikasi" {{ old('kategori', $helpdesk['kategori']) == 'Aplikasi' ? 'selected' : '' }}>Aplikasi</option>
                            <option value="Lainnya" {{ old('kategori', $helpdesk['kategori']) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('helpdesk.index') }}" class="btn btn-secondary btn-rounded shadow-sm px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
