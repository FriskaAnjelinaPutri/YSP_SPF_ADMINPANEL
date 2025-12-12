@extends('layouts.app')

@section('title', 'Edit Pengguna')

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
                <i class="bi bi-pencil-square me-2"></i>Edit Pengguna
            </h3>
            <small class="text-muted">Perbarui data pengguna</small>
        </div>
        <a href="{{ route('user.index') }}" class="btn btn-secondary shadow-sm btn-rounded">
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
            <i class="bi bi-person-fill me-2"></i>Form Edit Pengguna
        </div>
        <div class="card-body px-4 py-4">
            <form action="{{ route('user.update', $user['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user['name']) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user['email']) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold">Password Baru (opsional)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label fw-semibold">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role', $user['role']) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="karyawan" {{ old('role', $user['role']) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary btn-rounded shadow-sm px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
