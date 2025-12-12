@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
<div class="container-fluid">
    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }
        h3, h5 { font-weight: 600; }
        .text-primary { color: #166534 !important; }
        .card {
            border-radius: 20px;
            border: none;
            background: #fff;
        }
        .card-header {
            border-top-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            color: #fff;
        }
        .table thead {
            background-color: #dcfce7;
        }
        .btn-primary {
            background-color: #22c55e;
            border: none;
        }
        .btn-primary:hover {
            background-color: #16a34a;
        }
         .btn-rounded {
            border-radius: 50px;
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-person-circle me-2"></i>Data Pengguna
            </h3>
            <small class="text-secondary">Manajemen pengguna sistem</small>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2"></i>Daftar Pengguna
            </h5>
            <form method="GET" action="{{ route('user.index') }}" class="d-flex" style="width: 280px;">
                <input name="search" type="text" class="form-control"
                    placeholder="Cari pengguna..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-light ms-2"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user['name'] }}</strong></td>
                            <td>{{ $user['email'] }}</td>
                            <td><span class="badge bg-primary">{{ $user['role'] }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('user.edit', $user['id']) }}"
                                    class="btn btn-sm btn-outline-warning me-1 btn-rounded" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('user.destroy', $user['id']) }}"
                                    style="display:inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-rounded"
                                        title="Hapus">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2 fs-5"></i>Belum ada data pengguna.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
