@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="container-fluid py-4">

    {{-- ==================== CUSTOM STYLES ==================== --}}
    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: 15px;
            border: none;
            animation: fadeInUp 0.5s ease;
        }

        .card-header {
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            color: #fff;
            font-weight: 600;
        }

        .shadow-soft {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .btn-rounded {
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-rounded:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: #22c55e;
            border: none;
        }

        .btn-primary:hover {
            background-color: #16a34a;
        }

        .btn-secondary {
            background-color: #9ca3af;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #6b7280;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(34,197,94,0.25);
            border-color: #22c55e;
        }

        small.text-muted {
            display: block;
            margin-top: -4px;
            margin-bottom: 6px;
        }

        label.form-label {
            color: #166534;
        }

        .form-section-title {
            font-size: 1.1rem;
            color: #16a34a;
            margin-top: 1.2rem;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0.4rem;
        }

        .alert {
            border-radius: 12px;
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

        .alert ul {
            margin-top: 0.5rem;
            margin-bottom: 0;
            padding-left: 1.3rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    {{-- ==================== HEADER ==================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success mb-0">
                <i class="bi bi-person-plus-fill me-2"></i>Tambah Karyawan
            </h3>
            <small class="text-muted">Isi data karyawan baru dengan lengkap</small>
        </div>
        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary shadow-sm btn-rounded">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    {{-- ==================== ALERTS ==================== --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-soft" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Mohon periksa kembali inputan Anda.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-soft" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ==================== FORM CARD ==================== --}}
    <div class="card shadow-soft">
        <div class="card-header bg-sp-primary text-white fw-semibold">
            <i class="bi bi-person-fill me-2"></i>Form Tambah Karyawan
        </div>

        <div class="card-body px-4 py-4">
            <form action="{{ route('karyawan.store') }}" method="POST">
                @csrf

                {{-- ========= IDENTITAS DASAR ========= --}}
                <h6 class="form-section-title">Identitas Karyawan</h6>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label for="kar_nip" class="form-label fw-semibold">NIP</label>
                        <input type="text" class="form-control" id="kar_nip" name="kar_nip" value="{{ old('kar_nip') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_nik" class="form-label fw-semibold">NIK</label>
                        <input type="text" class="form-control" id="kar_nik" name="kar_nik" value="{{ old('kar_nik') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="kar_nama" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="kar_nama" name="kar_nama"
                            value="{{ old('kar_nama') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="kar_gelar_depan" class="form-label fw-semibold">Gelar Depan</label>
                        <input type="text" class="form-control" id="kar_gelar_depan" name="kar_gelar_depan"
                            value="{{ old('kar_gelar_depan') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="kar_gelar_belakang" class="form-label fw-semibold">Gelar Belakang</label>
                        <input type="text" class="form-control" id="kar_gelar_belakang" name="kar_gelar_belakang"
                            value="{{ old('kar_gelar_belakang') }}">
                    </div>
                </div>

                {{-- ========= LAHIR & ALAMAT ========= --}}
                <h6 class="form-section-title">Data Pribadi</h6>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label for="kar_lahir_tmp" class="form-label fw-semibold">Tempat Lahir</label>
                        <input type="text" class="form-control" id="kar_lahir_tmp" name="kar_lahir_tmp"
                            value="{{ old('kar_lahir_tmp') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_lahir_tgl" class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="kar_lahir_tgl" name="kar_lahir_tgl"
                            value="{{ old('kar_lahir_tgl') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_jekel" class="form-label fw-semibold">Jenis Kelamin</label>
                        <select class="form-select" id="kar_jekel" name="kar_jekel">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('kar_jekel') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('kar_jekel') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="kar_alamat" class="form-label fw-semibold">Alamat</label>
                        <input type="text" class="form-control" id="kar_alamat" name="kar_alamat"
                            value="{{ old('kar_alamat') }}">
                    </div>
                </div>

                {{-- ========= KONTAK ========= --}}
                <h6 class="form-section-title">Kontak & Komunikasi</h6>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label for="kar_email" class="form-label fw-semibold">Email Pribadi</label>
                        <input type="email" class="form-control" id="kar_email" name="kar_email"
                            value="{{ old('kar_email') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_email_perusahaan" class="form-label fw-semibold">Email Perusahaan</label>
                        <input type="email" class="form-control" id="kar_email_perusahaan"
                            name="kar_email_perusahaan" value="{{ old('kar_email_perusahaan') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_hp" class="form-label fw-semibold">No HP</label>
                        <input type="text" class="form-control" id="kar_hp" name="kar_hp"
                            value="{{ old('kar_hp') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_wa" class="form-label fw-semibold">No WA</label>
                        <input type="text" class="form-control" id="kar_wa" name="kar_wa"
                            value="{{ old('kar_wa') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_telegram" class="form-label fw-semibold">Telegram</label>
                        <input type="text" class="form-control" id="kar_telegram" name="kar_telegram"
                            value="{{ old('kar_telegram') }}">
                    </div>
                </div>

                {{-- ========= DOKUMEN ========= --}}
                <h6 class="form-section-title">Data Administratif</h6>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label for="kar_norek" class="form-label fw-semibold">No Rekening</label>
                        <input type="text" class="form-control" id="kar_norek" name="kar_norek"
                            value="{{ old('kar_norek') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_nobpjs" class="form-label fw-semibold">No BPJS</label>
                        <input type="text" class="form-control" id="kar_nobpjs" name="kar_nobpjs"
                            value="{{ old('kar_nobpjs') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_nojamsostek" class="form-label fw-semibold">No Jamsostek</label>
                        <input type="text" class="form-control" id="kar_nojamsostek" name="kar_nojamsostek"
                            value="{{ old('kar_nojamsostek') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_npwp" class="form-label fw-semibold">NPWP</label>
                        <input type="text" class="form-control" id="kar_npwp" name="kar_npwp"
                            value="{{ old('kar_npwp') }}">
                    </div>
                </div>

                {{-- ========= MASTER DATA ========= --}}
                @php
                    function selectOption($data, $oldKey, $valueKey, $nameKey)
                    {
                        if (empty($data)) return '<!-- No data available -->';
                        $out = '';
                        foreach ($data as $item) {
                            $val = is_array($item) ? $item[$valueKey] ?? '' : $item->$valueKey ?? '';
                            $name = is_array($item) ? $item[$nameKey] ?? '' : $item->$nameKey ?? '';
                            $sel = old($oldKey) == $val ? 'selected' : '';
                            $out .= "<option value='{$val}' {$sel}>{$name}</option>";
                        }
                        return $out;
                    }
                @endphp

                <h6 class="form-section-title">Master Data Karyawan</h6>
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label for="agama_kode" class="form-label fw-semibold">Agama</label>
                        <select class="form-select" id="agama_kode" name="agama_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($agamas, 'agama_kode', 'agama_kode', 'agama_nama') !!}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="profesi_kode" class="form-label fw-semibold">Profesi</label>
                        <select class="form-select" id="profesi_kode" name="profesi_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($profesis, 'profesi_kode', 'profesi_kode', 'profesi_nama') !!}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="unit_kode" class="form-label fw-semibold">Unit</label>
                        <select class="form-select" id="unit_kode" name="unit_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($units, 'unit_kode', 'unit_kode', 'unit_nama') !!}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="jabatan_kode" class="form-label fw-semibold">Jabatan</label>
                        <select class="form-select" id="jabatan_kode" name="jabatan_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($jabatans, 'jabatan_kode', 'jabatan_kode', 'jabatan_nama') !!}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="golongan_kode" class="form-label fw-semibold">Golongan</label>
                        <select class="form-select" id="golongan_kode" name="golongan_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($golongans, 'golongan_kode', 'golongan_kode', 'golongan_nama') !!}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="status_kode" class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="status_kode" name="status_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($statuses, 'status_kode', 'status_kode', 'status_nama') !!}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tipe_kode" class="form-label fw-semibold">Tipe</label>
                        <select class="form-select" id="tipe_kode" name="tipe_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($tipes, 'tipe_kode', 'tipe_kode', 'tipe_nama') !!}
                        </select>
                    </div>
                </div>

                {{-- ========= ACTION BUTTONS ========= --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary btn-rounded shadow-sm px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
