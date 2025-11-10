@extends('layouts.app')

@section('title', 'Data Cuti')

@section('content')
<div class="container-fluid py-4">

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
    transition: all 0.3s ease;
    background: #fff;
    border: none;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}
.card-header {
    border-top-left-radius: 20px !important;
    border-top-right-radius: 20px !important;
    background: linear-gradient(90deg, #16a34a, #22c55e);
    color: #fff;
}

/* === Table === */
.table-hover tbody tr:hover {
    background-color: rgba(22,101,52,0.08);
    transition: background-color 0.2s ease;
    cursor: pointer;
}
.table-hover tr.active-row {
    background-color: #dcfce7 !important;
    transition: background-color 0.3s ease;
}

.table th, .table td {
    vertical-align: middle;
    padding: 12px 15px;
}
.table thead {
    background-color: #dcfce7;
}
.table td strong { font-size: 0.95rem; color: #14532d; }
.table td small { font-size: 0.75rem; color: #6b7280; }

/* === Badges === */
.badge-status {
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
    border-radius: 12px;
    font-weight: 500;
    text-transform: capitalize;
}
.badge-pending { background: #facc15; color: #000; }
.badge-approved { background: #22c55e; color: #fff; }
.badge-rejected { background: #dc2626; color: #fff; }

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

.btn-outline-warning {
    border-color: #facc15;
    color: #ca8a04;
}
.btn-outline-warning:hover {
    background-color: #facc15;
    color: black;
}
.btn-outline-danger {
    border-color: #dc2626;
    color: #dc2626;
}
.btn-outline-danger:hover {
    background-color: #dc2626;
    color: white;
}

/* === Alerts === */
.alert {
    border-radius: 15px;
    padding: 12px 18px;
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

/* === Responsive === */
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 0.85rem;
    }
}
@media (max-width: 576px) {
    table th:nth-child(3),
    table td:nth-child(3),
    table th:nth-child(4),
    table td:nth-child(4) {
        display: none;
    }
}
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
