@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="container-fluid">

    {{-- Custom Styles Dashboard --}}
    <style>
        body { background-color: #f4f6f9; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 20px; transition: 0.3s; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(0,0,0,0.1); }
        .card-header { border-top-left-radius: 20px !important; border-top-right-radius: 20px !important; background: linear-gradient(90deg, #118ab2, #06d6a0); color: #fff; }
        .shadow-soft { box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .fw-semibold { font-weight: 600 !important; }
        .table-hover tbody tr:hover { background-color: rgba(17,138,178,0.08); transition: 0.2s; }
        .table th, .table td { vertical-align: middle; }
        .badge { font-size: 0.75rem; padding: 0.4em 0.8em; border-radius: 12px; }
        .badge-success { background: linear-gradient(45deg,#06d6a0,#118ab2); color: #fff; }
        .badge-warning { background: linear-gradient(45deg,#ffd166,#ef476f); color: #fff; }
        .badge-primary { background: linear-gradient(45deg,#118ab2,#06d6a0); color: #fff; }
        .badge-danger { background: linear-gradient(45deg,#ef476f,#ffd166); color: #fff; }
        .text-secondary { color: #6c757d !important; }
        small { display: block; font-size: 0.75rem; color: #6c757d; }
        .btn-rounded { border-radius: 50px; transition: 0.2s; }
        .btn-rounded:hover { transform: scale(1.05); }
        td.text-center .btn { margin-bottom: 2px; }

        /* Search */
        #searchInput { transition: all 0.3s; }
        #searchInput:focus { box-shadow: 0 0 10px rgba(17,138,178,0.3); }

        /* Modal */
        .modal-body { max-height: 70vh; overflow-y: auto; }
        .modal-body table th { width: 180px; font-weight: 600; color: #118ab2; }
        .modal-body table td { word-break: break-word; }
        .modal-content { border-radius: 20px; }
        .modal-header { background: linear-gradient(90deg,#118ab2,#06d6a0); color: #fff; }
        @media (max-width: 768px) {
            .modal-body table th { width: 120px; font-size: 0.75rem; }
            .modal-body table td { font-size: 0.75rem; }
        }

        /* Spinner Center */
        .spinner-wrapper { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 200px; }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-people-fill me-2"></i>Data Karyawan
            </h3>
            <small class="text-secondary">Manajemen data karyawan Rumah Sakit</small>
        </div>
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary shadow-sm btn-rounded">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Karyawan
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-soft" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error') || isset($error))
        <div class="alert alert-danger alert-dismissible fade show shadow-soft" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') ?? $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card border-0 shadow-soft mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2"></i>Daftar Karyawan
            </h5>
            <form method="GET" action="{{ route('karyawan.index') }}" class="d-flex" style="width: 280px;" id="searchForm">
                <input name="search" id="searchInput" type="text" class="form-control border-end-0" placeholder="Cari karyawan..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-light border-start-0"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-white">
                        <tr>
                            <th>Nama</th>
                            <th>No HP</th>
                            <th>Golongan</th>
                            <th>Jabatan</th>
                            <th>Unit</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="karyawanTbody">
                        @forelse($karyawans as $kar)
                            <tr>
                                <td>
                                    <strong>{{ $kar->kar_nama }}</strong>
                                    <small>{{ $kar->kar_email }}</small>
                                </td>
                                <td>{{ $kar->kar_hp ?? '-' }}</td>
                                <td><span class="badge badge-success">{{ $kar->golongan->golongan_nama ?? '-' }}</span></td>
                                <td><span class="badge badge-warning">{{ $kar->jabatan->jabatan_nama ?? '-' }}</span></td>
                                <td><span class="badge badge-primary">{{ $kar->unit->unit_nama ?? '-' }}</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info me-1 btn-rounded" title="Detail"
                                        data-kar_kode="{{ $kar->kar_kode }}" data-bs-toggle="modal" data-bs-target="#karyawanDetailModal">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('karyawan.edit', $kar->kar_kode) }}" class="btn btn-sm btn-outline-warning me-1 btn-rounded" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('karyawan.destroy', $kar->kar_kode) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
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
                                    <i class="bi bi-inbox me-2 fs-5"></i>Belum ada data karyawan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Detail Karyawan --}}
<div class="modal fade" id="karyawanDetailModal" tabindex="-1" aria-labelledby="karyawanDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content shadow-soft">
      <div class="modal-header">
        <h5 class="modal-title" id="karyawanDetailModalLabel">Detail Karyawan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="detailSpinner" class="spinner-wrapper">
          <div class="spinner-border text-primary" role="status"></div>
          <div class="mt-2">Memuat data...</div>
        </div>
        <div id="detailContent" class="d-none">
          <table class="table table-borderless table-striped">
            <tbody>
              <tr><th>Kode</th><td id="detail_kar_kode"></td></tr>
              <tr><th>NIP</th><td id="detail_kar_nip"></td></tr>
              <tr><th>NIK</th><td id="detail_kar_nik"></td></tr>
              <tr><th>Nama</th><td id="detail_kar_nama"></td></tr>
              <tr><th>Gelar Depan</th><td id="detail_kar_gelar_depan"></td></tr>
              <tr><th>Gelar Belakang</th><td id="detail_kar_gelar_belakang"></td></tr>
              <tr><th>Tempat Lahir</th><td id="detail_kar_lahir_tmp"></td></tr>
              <tr><th>Tanggal Lahir</th><td id="detail_kar_lahir_tgl"></td></tr>
              <tr><th>Jenis Kelamin</th><td id="detail_kar_jekel"></td></tr>
              <tr><th>Alamat</th><td id="detail_kar_alamat"></td></tr>
              <tr><th>Email</th><td id="detail_kar_email"></td></tr>
              <tr><th>Email Perusahaan</th><td id="detail_kar_email_perusahaan"></td></tr>
              <tr><th>No HP</th><td id="detail_kar_hp"></td></tr>
              <tr><th>WA</th><td id="detail_kar_wa"></td></tr>
              <tr><th>Telegram</th><td id="detail_kar_telegram"></td></tr>
              <tr><th>No Rekening</th><td id="detail_kar_norek"></td></tr>
              <tr><th>No BPJS</th><td id="detail_kar_nobpjs"></td></tr>
              <tr><th>No Jamsostek</th><td id="detail_kar_nojamsostek"></td></tr>
              <tr><th>NPWP</th><td id="detail_kar_npwp"></td></tr>
              <tr><th>Agama</th><td id="detail_agama"></td></tr>
              <tr><th>Profesi</th><td id="detail_profesi"></td></tr>
              <tr><th>Status</th><td id="detail_status"></td></tr>
              <tr><th>Unit</th><td id="detail_unit"></td></tr>
              <tr><th>Jabatan</th><td id="detail_jabatan"></td></tr>
              <tr><th>Golongan</th><td id="detail_golongan"></td></tr>
              <tr><th>Tipe</th><td id="detail_tipe"></td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

{{-- Script pencarian dan modal --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    let timeout;

    searchInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => searchForm.submit(), 500);
    });

    searchForm.addEventListener('submit', function() {
        const tbody = document.getElementById('karyawanTbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2">Mencari data...</div>
                </td>
            </tr>
        `;
    });

    var karyawanDetailModal = document.getElementById('karyawanDetailModal');
    karyawanDetailModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var kar_kode = button.getAttribute('data-kar_kode');

        var spinner = document.getElementById('detailSpinner');
        var content = document.getElementById('detailContent');

        spinner.classList.remove('d-none');
        content.classList.add('d-none');

        fetch(`{{ $apiBase ?? 'http://127.0.0.1:8000/api' }}/karyawan/${kar_kode}`, {
            headers: {
                'Authorization': 'Bearer {{ session('api_token') }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            spinner.classList.add('d-none');
            content.classList.remove('d-none');

            const kar = data.data || {};
            document.getElementById('detail_kar_kode').innerText = kar.kar_kode || '-';
            document.getElementById('detail_kar_nip').innerText = kar.kar_nip || '-';
            document.getElementById('detail_kar_nik').innerText = kar.kar_nik || '-';
            document.getElementById('detail_kar_nama').innerText = kar.kar_nama || '-';
            document.getElementById('detail_kar_gelar_depan').innerText = kar.kar_gelar_depan || '-';
            document.getElementById('detail_kar_gelar_belakang').innerText = kar.kar_gelar_belakang || '-';
            document.getElementById('detail_kar_lahir_tmp').innerText = kar.kar_lahir_tmp || '-';
            document.getElementById('detail_kar_lahir_tgl').innerText = kar.kar_lahir_tgl || '-';
            document.getElementById('detail_kar_jekel').innerText = kar.kar_jekel || '-';
            document.getElementById('detail_kar_alamat').innerText = kar.kar_alamat || '-';
            document.getElementById('detail_kar_email').innerText = kar.kar_email || '-';
            document.getElementById('detail_kar_email_perusahaan').innerText = kar.kar_email_perusahaan || '-';
            document.getElementById('detail_kar_hp').innerText = kar.kar_hp || '-';
            document.getElementById('detail_kar_wa').innerText = kar.kar_wa || '-';
            document.getElementById('detail_kar_telegram').innerText = kar.kar_telegram || '-';
            document.getElementById('detail_kar_norek').innerText = kar.kar_norek || '-';
            document.getElementById('detail_kar_nobpjs').innerText = kar.kar_nobpjs || '-';
            document.getElementById('detail_kar_nojamsostek').innerText = kar.kar_nojamsostek || '-';
            document.getElementById('detail_kar_npwp').innerText = kar.kar_npwp || '-';
            document.getElementById('detail_agama').innerText = kar.agama?.agama_nama || '-';
            document.getElementById('detail_profesi').innerText = kar.profesi?.profesi_nama || '-';
            document.getElementById('detail_status').innerText = kar.status?.status_nama || '-';
            document.getElementById('detail_unit').innerText = kar.unit?.unit_nama || '-';
            document.getElementById('detail_jabatan').innerText = kar.jabatan?.jabatan_nama || '-';
            document.getElementById('detail_golongan').innerText = kar.golongan?.golongan_nama || '-';
            document.getElementById('detail_tipe').innerText = kar.tipe?.tipe_nama || '-';
        })
        .catch(err => {
            spinner.innerHTML = `<p class="text-danger">Gagal memuat data. Coba lagi.</p>`;
            content.classList.add('d-none');
        });
    });
});
</script>
@endsection
