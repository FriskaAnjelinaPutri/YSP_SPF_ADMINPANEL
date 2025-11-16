{{-- resources/views/jadwal/hasil.blade.php --}}
@extends('layouts.app') {{-- atau layouts.app, sesuaikan --}}

@section('title', 'Hasil Generate Jadwal Kerja')

@section('content')
<div class="container-fluid">

    {{-- Custom Styles Dashboard --}}
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
    .table thead { background-color: #dcfce7; }
    .table td strong { font-size: 0.95rem; color: #14532d; }
    .table td small { font-size: 0.75rem; color: #6b7280; }

    /* === Badges === */
    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.8em;
        border-radius: 12px;
        font-weight: 500;
        text-transform: capitalize;
    }
    .badge-success { background: #22c55e; color: #fff; }
    .badge-warning { background: #facc15; color: #000; }
    .badge-primary { background: #16a34a; color: #fff; }
    .badge-danger { background: #dc2626; color: #fff; }
    .badge-info { background: #38bdf8; color: #fff; }

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

    /* === Alerts === */
    .alert {
        border-radius: 15px;
        padding: 12px 18px;
        font-size: 0.9rem;
    }
    .alert-success { background-color: #dcfce7; color: #166534; }
    .alert-danger { background-color: #fee2e2; color: #991b1b; }

    </style>

    <h1 class="mt-4">Hasil Generate Jadwal Kerja</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') ?? $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($error))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">
            <i class="bi bi-filter"></i> Filter Jadwal
        </div>
        <div class="card-body">
            <form action="{{ route('jadwal.hasil') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select" required>
                            @php $bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                    {{ $bulanNama[$i-1] }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select" required>
                            @for($y = date('Y')-5; $y <= date('Y')+5; $y++)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            <i class="bi bi-table"></i>
            Jadwal Kerja Bulan {{ $bulanNama[$bulan-1] }} {{ $tahun }}
        </div>
        <div class="card-body">
            @if($paginator && $paginator->total() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Karyawan</th>
                                <th>Tanggal</th>
                                <th>Jadwal</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paginator as $index => $item)
                                <tr>
                                    <td>{{ $paginator->firstItem() + $index }}</td>
                                    <td>{{ $item['kar_nama'] ?? 'N/A' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item['tanggal'])->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $item['jadwal_nama'] ?? 'Libur' }}
                                        </span>
                                    </td>
                                    <td>{{ $item['jam_mulai'] ? \Carbon\Carbon::parse($item['jam_mulai'])->format('H:i') : '-' }}</td>
                                    <td>{{ $item['jam_selesai'] ? \Carbon\Carbon::parse($item['jam_selesai'])->format('H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $paginator->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x display-1"></i>
                    <p class="mt-3 fs-5">Tidak ada jadwal kerja untuk periode ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
