@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-people-fill me-2"></i>Data Karyawan
            </h3>
            <small class="text-muted">Manajemen data karyawan Rumah Sakit</small>
        </div>
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Karyawan
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-secondary">
                <i class="bi bi-list-ul me-2 text-primary"></i>Daftar Karyawan
            </h5>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input id="searchKaryawan" type="text" class="form-control border-start-0" placeholder="Cari karyawan...">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelKaryawan">
                    <thead class="bg-primary text-white">
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
                    <tbody id="dataKaryawan">
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <div class="spinner-border text-primary" role="status"></div>
                                <div>Memuat data...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk Fetch API --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const apiURL = "http://127.0.0.1:8000/api/karyawan";
    const tbody = document.getElementById('dataKaryawan');
    const searchInput = document.getElementById('searchKaryawan');

    // Ambil token dari localStorage (pastikan diset waktu login)
    const token = localStorage.getItem('token');

    // Kalau belum ada token, beri pesan error
    if (!token) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-danger py-4">
                    <i class="bi bi-x-circle me-2"></i>Token login tidak ditemukan.<br>
                    Silakan login kembali.
                </td>
            </tr>`;
        return;
    }

    // Fungsi untuk ambil data dari API
    function loadKaryawan(query = '') {
        fetch(apiURL, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal mengakses API. Status: ' + response.status);
            }
            return response.json();
        })
        .then(result => {
            tbody.innerHTML = '';

            if (!result.success || !Array.isArray(result.data) || result.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox me-2 fs-5"></i>Belum ada data karyawan
                        </td>
                    </tr>`;
                return;
            }

            // Filter pencarian
            const filtered = result.data.filter(k =>
                k.kar_nama?.toLowerCase().includes(query.toLowerCase()) ||
                k.kar_email?.toLowerCase().includes(query.toLowerCase()) ||
                k.kar_kode?.toLowerCase().includes(query.toLowerCase())
            );

            // Render baris tabel
            filtered.forEach(kar => {
                const jabatan = kar.jabatan?.nama ?? '-';
                const unit = kar.unit?.nama ?? '-';
                const email = kar.kar_email ?? '-';
                const hp = kar.kar_hp ?? '-';

                tbody.innerHTML += `
                    <tr>
                        <td class="fw-semibold text-secondary">${kar.kar_kode}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:35px; height:35px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="ms-2">
                                    <div class="fw-semibold">${kar.kar_nama}</div>
                                    <small class="text-muted">${email}</small>
                                </div>
                            </div>
                        </td>
                        <td>${email}</td>
                        <td>${hp}</td>
                        <td><span class="badge bg-info text-dark">${jabatan}</span></td>
                        <td><span class="badge bg-secondary">${unit}</span></td>
                        <td class="text-center">
                            <a href="/karyawan/${kar.kar_kode}/edit" class="btn btn-sm btn-outline-warning me-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" onclick="hapusKaryawan('${kar.id}')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => {
            console.error(err);
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        <i class="bi bi-x-circle me-2"></i>Gagal memuat data karyawan.<br>
                        ${err.message}
                    </td>
                </tr>`;
        });
    }

    // Hapus karyawan
    window.hapusKaryawan = function(id) {
        if (!confirm("Yakin ingin menghapus karyawan ini?")) return;

        fetch(`/api/karyawan/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(result => {
            alert(result.message);
            loadKaryawan();
        })
        .catch(err => alert("Gagal menghapus karyawan"));
    };

    // Event pencarian realtime
    searchInput.addEventListener('keyup', () => {
        const query = searchInput.value.trim();
        loadKaryawan(query);
    });

    // Panggil pertama kali
    loadKaryawan();
});
</script>
@endsection
