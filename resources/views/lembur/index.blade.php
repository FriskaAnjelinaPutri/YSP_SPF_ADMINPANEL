@extends('layouts.app')

@section('title', 'Data Lembur')

@section('content')
<div class="container-fluid py-4">

{{-- === Custom Styles === --}}
<style>
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

/* === Button === */
.btn-rounded { border-radius: 50px; transition: transform 0.2s ease; }
.btn-rounded:hover { transform: scale(1.05); }

.btn-primary {
    background-color: #22c55e;
    border: none;
}
.btn-primary:hover { background-color: #16a34a; }

.btn-outline-warning:hover { background-color: #facc15; color: #fff; }
.btn-outline-info:hover { background-color: #0ea5e9; color: #fff; }
.btn-outline-danger:hover { background-color: #ef4444; color: #fff; }

/* === Badge === */
.badge-status {
    font-size: 0.8rem;
    padding: 0.45em 0.8em;
    border-radius: 12px;
    font-weight: 500;
}
.badge-pending { background-color: #facc15; color: #fff; }
.badge-approved { background-color: #22c55e; color: #fff; }
.badge-rejected { background-color: #ef4444; color: #fff; }

/* === Form === */
.form-label { font-weight: 500; color: #166534; }
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
.alert-success {
    background-color: #dcfce7;
    color: #166534;
}
.alert-danger {
    background-color: #fee2e2;
    color: #991b1b;
}

/* === Table === */
.table th {
    background-color: #f9fafb;
    font-weight: 600;
    color: #166534;
}
.table-hover tbody tr:hover {
    background-color: #f0fdf4;
}
</style>

{{-- === Header === --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-calendar-plus me-2"></i>Data Lembur Karyawan
        </h3>
        <small class="text-secondary">Manajemen data pengajuan lembur</small>
    </div>
    <a href="{{ route('lembur.create') }}" class="btn btn-primary shadow-sm btn-rounded">
        <i class="bi bi-plus-circle me-1"></i> Tambah Pengajuan
    </a>
</div>

{{-- === Alerts === --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error') || isset($error))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') ?? $error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- === Filter Card === --}}
<div class="card mb-4">
    <div class="card-header fw-semibold">
        <i class="bi bi-filter me-2"></i>Filter Data Lembur
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('lembur.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="periode" class="form-label">Periode</label>
                    <input type="month" class="form-control" name="periode" id="periode" value="{{ $periode }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
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
                    <button type="submit" class="btn btn-primary w-100 btn-rounded">
                        <i class="bi bi-search me-1"></i> Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- === Table Card === --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-list-ul me-2"></i>Daftar Pengajuan Lembur
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Alasan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lemburs as $lembur)
                        <tr>
                            <td>
                                <strong>{{ $lembur['karyawan']['kar_nama'] ?? 'N/A' }}</strong><br>
                                <small class="text-secondary">{{ $lembur['karyawan']['kar_nip'] ?? '' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($lembur['tanggal'])->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($lembur['jam_mulai'])->format('H:i') }} - {{ \Carbon\Carbon::parse($lembur['jam_selesai'])->format('H:i') }}</td>
                            <td>{{ Str::limit($lembur['alasan'], 40) }}</td>
                            <td class="text-center">
                                @php
                                    $statusClass = 'secondary';
                                    if ($lembur['status'] == 'Approved') $statusClass = 'approved';
                                    if ($lembur['status'] == 'Pending') $statusClass = 'pending';
                                    if ($lembur['status'] == 'Rejected') $statusClass = 'rejected';
                                @endphp
                                <span class="badge badge-status badge-{{ $statusClass }}">{{ $lembur['status'] }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('lembur.show', $lembur['id']) }}" class="btn btn-sm btn-outline-info btn-rounded" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('lembur.edit', $lembur['id']) }}" class="btn btn-sm btn-outline-warning btn-rounded" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('lembur.destroy', $lembur['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-rounded" title="Hapus">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2 fs-5"></i>Tidak ada data lembur untuk periode ini.
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
