@extends('layouts.app')

@section('title', 'Data Cuti')

@section('content')
<div class="container-fluid py-4">

    <style>
        body { background-color: #f8fafc; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 15px; border: none; box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .card-header { border-top-left-radius: 15px !important; border-top-right-radius: 15px !important; background-color: #118ab2 !important; }
        .btn-rounded { border-radius: 50px; }
        .badge-status { font-size: 0.8rem; padding: 0.4em 0.8em; }
        .badge-pending { background-color: #ffc107; color: #fff; }
        .badge-approved { background-color: #28a745; color: #fff; }
        .badge-rejected { background-color: #dc3545; color: #fff; }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-calendar-check me-2"></i>Data Cuti Karyawan
            </h3>
            <small class="text-muted">Manajemen data pengajuan cuti</small>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error') || isset($error))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') ?? $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Quick Filter Buttons --}}
    <div class="mb-4">
        <a href="{{ route('cuti.index', ['periode' => $periode, 'kar_kode' => request('kar_kode')]) }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }} btn-rounded">Semua</a>
        <a href="{{ route('cuti.index', ['status' => 'Pending', 'periode' => $periode, 'kar_kode' => request('kar_kode')]) }}" class="btn {{ request('status') == 'Pending' ? 'btn-warning text-white' : 'btn-outline-warning' }} btn-rounded">Pending</a>
        <a href="{{ route('cuti.index', ['status' => 'Approved', 'periode' => $periode, 'kar_kode' => request('kar_kode')]) }}" class="btn {{ request('status') == 'Approved' ? 'btn-success' : 'btn-outline-success' }} btn-rounded">Approved</a>
        <a href="{{ route('cuti.index', ['status' => 'Rejected', 'periode' => $periode, 'kar_kode' => request('kar_kode')]) }}" class="btn {{ request('status') == 'Rejected' ? 'btn-danger' : 'btn-outline-danger' }} btn-rounded">Rejected</a>
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-header text-white fw-semibold">
            <i class="bi bi-filter me-2"></i>Filter Data Cuti
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('cuti.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="periode" class="form-label">Periode</label>
                        <input type="month" name="periode" id="periode" value="{{ $periode }}" class="form-control">
                    </div>
                    {{-- <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
                            <option value="Approved" {{ request('status')=='Approved'?'selected':'' }}>Approved</option>
                            <option value="Rejected" {{ request('status')=='Rejected'?'selected':'' }}>Rejected</option>
                        </select>
                    </div> --}}
                    <div class="col-md-3">
                        <label for="kar_kode" class="form-label">Karyawan</label>
                        <select name="kar_kode" id="kar_kode" class="form-select">
                            <option value="">Semua Karyawan</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k['kar_kode'] }}" {{ request('kar_kode') == $k['kar_kode'] ? 'selected' : '' }}>
                                    {{ $k['kar_nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2"></i>Daftar Pengajuan Cuti</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jenis Cuti</th>
                            <th>Alasan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cutis as $cuti)
                            <tr>
                                <td>
                                    <strong>{{ $cuti['karyawan']['kar_nama'] ?? '-' }}</strong><br>
                                    <small>{{ $cuti['karyawan']['kar_nip'] ?? '' }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($cuti['tanggal_mulai'])->format('d M Y') }} - {{ \Carbon\Carbon::parse($cuti['tanggal_selesai'])->format('d M Y') }}</td>
                                <td>{{ $cuti['jenis_cuti'] }}</td>
                                <td>{{ Str::limit($cuti['alasan'], 40) }}</td>
                                <td class="text-center">
                                    @php
                                        $statusClass = strtolower($cuti['status']);
                                    @endphp
                                    <span class="badge badge-status badge-{{ $statusClass }}">{{ $cuti['status'] }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('cuti.edit', $cuti['id']) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('cuti.destroy', $cuti['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox me-2 fs-5"></i>Tidak ada data cuti untuk periode ini.
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
