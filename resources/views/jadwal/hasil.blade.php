@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Hasil Generate Jadwal Kerja</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Hasil Jadwal Kerja</li>
    </ol>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i class="bi bi-filter me-1"></i>
            Filter Jadwal Kerja
        </div>
        <div class="card-body">
            <form action="{{ route('jadwal.hasil') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ (int)$bulan === $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            @for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++)
                                <option value="{{ $i }}" {{ (int)$tahun === $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <i class="bi bi-table me-1"></i>
            Data Jadwal Kerja untuk {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->monthName }} {{ $tahun }}
        </div>
        <div class="card-body">
            @if ($paginator && $paginator->total() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Karyawan</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jadwal</th>
                                <th scope="col">Jam Mulai</th>
                                <th scope="col">Jam Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paginator as $index => $jadwalKerja)
                                <tr>
                                    <th scope="row">{{ $paginator->firstItem() + $index }}</th>
                                    <td>{{ $jadwalKerja['kar_nama'] ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwalKerja['tanggal'])->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $jadwalKerja['jadwal_nama'] ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($jadwalKerja['jam_mulai'])->format('H:i') ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwalKerja['jam_selesai'])->format('H:i') ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $paginator->appends(request()->except('page'))->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                    <p class="mt-3">Tidak ada jadwal kerja yang ditemukan untuk bulan dan tahun ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
