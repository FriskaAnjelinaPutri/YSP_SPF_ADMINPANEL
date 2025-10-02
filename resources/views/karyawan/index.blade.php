@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Daftar Karyawan</h3>
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Karyawan
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Jabatan</th>
                            <th>Unit</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawans as $kar)
                            <tr>
                                <td>{{ $kar['kar_kode'] ?? '-' }}</td>
                                <td>{{ $kar['kar_nama'] ?? '-' }}</td>
                                <td>{{ $kar['kar_email'] ?? '-' }}</td>
                                <td>{{ $kar['kar_hp'] ?? '-' }}</td>
                                <td>{{ $kar['jabatan']['nama'] ?? '-' }}</td>
                                <td>{{ $kar['unit']['nama'] ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('karyawan.edit', $kar['kar_kode']) }}"
                                       class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('karyawan.destroy', $kar['kar_kode']) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus karyawan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <i class="bi bi-inbox me-2"></i>Belum ada data karyawan
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
