@extends('layouts.app')

@section('title', 'Manajemen Tipe')

@section('content')
<div class="container-fluid">

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

.btn-outline-danger {
    border-color: #dc2626;
    color: #dc2626;
}
.btn-outline-danger:hover {
    background-color: #dc2626;
    color: white;
}

/* === Table === */
.table-hover tbody tr:hover {
    background-color: rgba(22,101,52,0.08);
    transition: background-color 0.2s ease;
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
</style>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-tags-fill me-2"></i>Daftar Tipe
        </h3>
        <small class="text-secondary">Kelola tipe pola kerja karyawan</small>
    </div>
    <a href="{{ route('tipe.create') }}" class="btn btn-primary shadow-sm btn-rounded">
        <i class="bi bi-plus-circle me-1"></i> Tambah Tipe
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
            <i class="bi bi-list-task me-2"></i>Data Tipe
        </h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="tipeTable" class="table table-hover align-middle mb-0">
                <thead class="text-white">
                    <tr>
                        <th>#</th>
                        <th>Kode Tipe</th>
                        <th>Nama Tipe</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tipes as $index => $tipe)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $tipe['tipe_kode'] }}</strong></td>
                            <td>{{ $tipe['tipe_nama'] }}</td>
                            <td>
                                @if($tipe['tipe_aktif'])
                                    <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill">Aktif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill">Nonaktif</span>
                                @endif
                            </td>
                            <td><small>{{ \Carbon\Carbon::parse($tipe['created_at'])->format('d M Y') }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('tipe.edit', $tipe['tipe_kode']) }}"
                                   class="btn btn-sm btn-outline-warning me-1 btn-rounded" title="Edit">
                                   <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('tipe.destroy', $tipe['tipe_kode']) }}" method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger btn-rounded"
                                            onclick="return confirm('Yakin ingin menghapus tipe {{ $tipe['tipe_nama'] }}?')" title="Hapus">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2 fs-5"></i>Tidak ada data tipe kerja.
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

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    if (window.jQuery && $.fn.DataTable) {
        $('#tipeTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ditemukan data yang cocok",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });
    }
});
</script>
@endsection
