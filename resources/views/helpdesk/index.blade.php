@extends('layouts.app')

@section('title', 'Data Helpdesk')

@section('content')
<div class="container-fluid">

    {{-- Custom Styles --}}
    <style>
        body { background-color: #f0fdf4; font-family: 'Poppins', sans-serif; }
        h3, h5 { font-weight: 600; }
        .text-primary { color: #166534 !important; }
        .card { border-radius: 20px; border: none; }
        .card-header { background: linear-gradient(90deg, #16a34a, #22c55e); color: #fff; border-top-left-radius: 20px; border-top-right-radius: 20px; }
        .table thead { background-color: #dcfce7; }
        .table th, .table td { vertical-align: middle; }
        .alert { border-radius: 15px; }
        .alert-success { background-color: #dcfce7; color: #166534; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; }
        .form-control, .form-select { border-radius: 8px; }
        .btn-primary { background-color: #22c55e; border: none; }
        .btn-primary:hover { background-color: #16a34a; }
        .badge { font-size: 0.8rem; padding: 0.4em 0.8em; border-radius: 12px; }
        .btn-rounded { border-radius: 50px; }
        .modal-content { border-radius: 20px; }
        .modal-header { background: linear-gradient(90deg,#16a34a,#22c55e); color: #fff; border-bottom: none; }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-headset me-2"></i>Data Helpdesk
            </h3>
            <small class="text-secondary">Manajemen tiket helpdesk</small>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('message'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error') || isset($error))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') ?? $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2"></i>Daftar Tiket Helpdesk
            </h5>
            <form method="GET" action="{{ url('/helpdesk') }}" id="filterForm" class="d-flex align-items-center">
                <label for="periode" class="form-label text-white me-2 mb-0">Periode:</label>
                <input type="month" name="periode" id="periode" class="form-control" value="{{ $periode ?? '' }}">
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID Tiket</th>
                            <th>Pelapor</th>
                            <th>Deskripsi</th>
                            <th>Tgl Dilaporkan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($helpdesks as $ticket)
                        <tr>
                            <td><span class="badge bg-primary">#{{ $ticket->id }}</span></td>
                            <td>{{ $ticket->nama_pelapor ?? 'N/A' }}</td>
                            <td>{{ Str::limit($ticket->deskripsi, 50) }}</td>
                            <td>{{ \Carbon\Carbon::parse($ticket->tanggal_dilaporkan)->format('d M Y H:i') }}</td>
                            <td>
                                @php
                                    $statusClass = 'bg-secondary';
                                    if ($ticket->status == 'Open') $statusClass = 'bg-danger';
                                    if ($ticket->status == 'In Progress') $statusClass = 'bg-warning text-dark';
                                    if ($ticket->status == 'Resolved') $statusClass = 'bg-success';
                                    if ($ticket->status == 'Closed') $statusClass = 'bg-dark';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $ticket->status }}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-info btn-rounded" title="Detail" data-bs-toggle="modal" data-bs-target="#detailModal" data-id="{{ $ticket->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="{{ route('helpdesk.edit', $ticket->id) }}" class="btn btn-sm btn-outline-warning btn-rounded" title="Update Status">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('helpdesk.destroy', $ticket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tiket ini?');">
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
                                <i class="bi bi-inbox me-2 fs-5"></i>Belum ada data helpdesk untuk periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Tiket Helpdesk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detailSpinner" class="text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Memuat data...</p>
                </div>
                <div id="detailContent" class="d-none">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 180px;">ID Tiket</th>
                            <td id="detail_id"></td>
                        </tr>
                        <tr>
                            <th>Nama Pelapor</th>
                            <td id="detail_user_nama"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Dilaporkan</th>
                            <td id="detail_tanggal_dilaporkan"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="detail_status"></td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td id="detail_deskripsi" style="white-space: pre-wrap;"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Filter Periode ---
    const periodeInput = document.getElementById('periode');
    if(periodeInput) {
        periodeInput.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }

    // --- Modal Detail ---
    const detailModal = document.getElementById('detailModal');
    if(detailModal) {
        detailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const ticketId = button.getAttribute('data-id');

            const spinner = document.getElementById('detailSpinner');
            const content = document.getElementById('detailContent');

            spinner.classList.remove('d-none');
            content.classList.add('d-none');

            // Fetch data
            fetch(`/helpdesk/${ticketId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const ticket = data.data;

                document.getElementById('detail_id').innerText = `#${ticket.id}`;
                document.getElementById('detail_user_nama').innerText = ticket.user_nama || 'N/A';

                const tgl = new Date(ticket.tanggal_dilaporkan);
                document.getElementById('detail_tanggal_dilaporkan').innerText = tgl.toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });

                document.getElementById('detail_deskripsi').innerText = ticket.deskripsi;

                // Status badge
                let statusClass = 'bg-secondary';
                if (ticket.status == 'Open') statusClass = 'bg-danger';
                if (ticket.status == 'In Progress') statusClass = 'bg-warning text-dark';
                if (ticket.status == 'Resolved') statusClass = 'bg-success';
                if (ticket.status == 'Closed') statusClass = 'bg-dark';
                document.getElementById('detail_status').innerHTML = `<span class="badge ${statusClass}">${ticket.status}</span>`;

                spinner.classList.add('d-none');
                content.classList.remove('d-none');
            })
            .catch(error => {
                spinner.innerHTML = `<p class="text-danger">Gagal memuat detail tiket. ${error.message}</p>`;
            });
        });
    }
});
</script>

@endsection
