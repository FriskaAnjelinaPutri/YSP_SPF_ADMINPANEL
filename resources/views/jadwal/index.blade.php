@extends('layouts.app')

@section('title', 'Daftar Jadwal')

@section('content')
    <div class="container-fluid">

        <style>
            /* === General === */
            body {
                background-color: #f4f6f9;
                font-family: 'Poppins', sans-serif;
            }

            h3,
            h5 {
                font-weight: 600;
            }

            .text-primary {
                color: #118ab2 !important;
            }

            .text-secondary {
                color: #6c757d !important;
            }

            /* === Card === */
            .card {
                border-radius: 20px;
                transition: all 0.3s ease;
                background: #fff;
            }

            .card:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                border-top-left-radius: 20px !important;
                border-top-right-radius: 20px !important;
                background: linear-gradient(90deg, #118ab2, #06d6a0);
                color: #fff;
            }

            /* === Table === */
            .table-hover tbody tr:hover {
                background-color: rgba(17, 138, 178, 0.08);
                transition: background-color 0.2s ease;
            }

            .table th,
            .table td {
                vertical-align: middle;
                padding: 12px 15px;
            }

            .table td strong {
                font-size: 0.95rem;
            }

            .table td small {
                font-size: 0.8rem;
                color: #6c757d;
            }

            /* === Badges === */
            .badge {
                font-size: 0.75rem;
                padding: 0.4em 0.8em;
                border-radius: 12px;
                font-weight: 500;
                text-transform: capitalize;
            }

            .badge-success {
                background: linear-gradient(45deg, #06d6a0, #118ab2);
                color: #fff;
            }

            .badge-warning {
                background: linear-gradient(45deg, #ffd166, #ef476f);
                color: #fff;
            }

            .badge-primary {
                background: linear-gradient(45deg, #118ab2, #06d6a0);
                color: #fff;
            }

            .badge-danger {
                background: linear-gradient(45deg, #ef476f, #ffd166);
                color: #fff;
            }

            /* === Buttons === */
            .btn-rounded {
                border-radius: 50px;
                transition: transform 0.2s ease;
            }

            .btn-rounded:hover {
                transform: scale(1.05);
            }

            td.text-center .btn {
                margin-bottom: 2px;
            }

            /* === Alerts === */
            .alert {
                border-radius: 15px;
                padding: 12px 18px;
                font-size: 0.9rem;
            }

            /* === Responsive === */
            @media (max-width: 768px) {

                .table th,
                .table td {
                    font-size: 0.85rem;
                }
            }
        </style>

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-0">
                    <i class="bi bi-calendar-week me-2"></i>Daftar Jadwal
                </h3>
                <small class="text-secondary">Manajemen jadwal kerja Rumah Sakit</small>
            </div>
            <a href="{{ route('jadwal.create') }}" class="btn btn-primary shadow-sm btn-rounded">
                <i class="bi bi-plus-circle me-1"></i> Tambah Jadwal
            </a>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-soft" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-soft" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Card Table --}}
        <div class="card border-0 shadow-soft mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-clock-history me-2"></i>Data Jadwal
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-white">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwals as $jadwal)
                                <tr>
                                    <td><strong>{{ $jadwal['jadwal_kode'] }}</strong></td>
                                    <td>{{ $jadwal['jadwal_nama'] }}</td>
                                    <td>{{ $jadwal['jam_mulai'] }}</td>
                                    <td>{{ $jadwal['jam_selesai'] }}</td>
                                    <td>
                                        @if ($jadwal['status'])
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                                Nonaktif
                                            </span>
                                        @endif


                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('jadwal.edit', $jadwal['jadwal_kode']) }}"
                                            class="btn btn-sm btn-outline-warning me-1 btn-rounded" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('jadwal.destroy', $jadwal['jadwal_kode']) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-rounded"
                                                onclick="return confirm('Hapus jadwal ini?')" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox me-2 fs-5"></i>Tidak ada data jadwal
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
