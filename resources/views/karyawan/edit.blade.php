@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="container-fluid">

    {{-- Custom Styles --}}
    <style>
        body { background-color: #f8fafc; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 15px; }
        .card-header { border-top-left-radius: 15px !important; border-top-right-radius: 15px !important; }
        .shadow-soft { box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        .fw-semibold { font-weight: 600 !important; }
        .btn-rounded { border-radius: 50px; }
        .form-control:focus { box-shadow: none; border-color: #118ab2; }
        small.text-muted { display: block; margin-top: -4px; margin-bottom: 6px; }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-pencil-square me-2"></i>Edit Karyawan
            </h3>
            <small class="text-muted">Perbarui data karyawan sesuai kebutuhan</small>
        </div>
        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary shadow-sm btn-rounded">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-soft" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Mohon periksa kembali inputan Anda.
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-soft" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card border-0 shadow-soft">
        <div class="card-header bg-sp-primary text-white fw-semibold">
            <i class="bi bi-person-fill me-2"></i>Form Edit Karyawan
        </div>
        <div class="card-body">
            <form action="{{ route('karyawan.update', $karyawan->kar_kode) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- NIP & NIK --}}
                    <div class="col-md-6">
                        <label for="kar_nip" class="form-label fw-semibold">NIP</label>
                        <input type="text" class="form-control" id="kar_nip" name="kar_nip"
                               value="{{ old('kar_nip', $karyawan->kar_nip) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_nik" class="form-label fw-semibold">NIK</label>
                        <input type="text" class="form-control" id="kar_nik" name="kar_nik"
                               value="{{ old('kar_nik', $karyawan->kar_nik) }}">
                    </div>

                    {{-- Nama & Gelar --}}
                    <div class="col-md-6">
                        <label for="kar_nama" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="kar_nama" name="kar_nama"
                               value="{{ old('kar_nama', $karyawan->kar_nama) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="kar_gelar_depan" class="form-label fw-semibold">Gelar Depan</label>
                        <input type="text" class="form-control" id="kar_gelar_depan" name="kar_gelar_depan"
                               value="{{ old('kar_gelar_depan', $karyawan->kar_gelar_depan) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="kar_gelar_belakang" class="form-label fw-semibold">Gelar Belakang</label>
                        <input type="text" class="form-control" id="kar_gelar_belakang" name="kar_gelar_belakang"
                               value="{{ old('kar_gelar_belakang', $karyawan->kar_gelar_belakang) }}">
                    </div>

                    {{-- Tempat & Tanggal Lahir --}}
                    <div class="col-md-6">
                        <label for="kar_lahir_tmp" class="form-label fw-semibold">Tempat Lahir</label>
                        <input type="text" class="form-control" id="kar_lahir_tmp" name="kar_lahir_tmp"
                               value="{{ old('kar_lahir_tmp', $karyawan->kar_lahir_tmp) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_lahir_tgl" class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="kar_lahir_tgl" name="kar_lahir_tgl"
                               value="{{ old('kar_lahir_tgl', $karyawan->kar_lahir_tgl) }}">
                    </div>

                    {{-- Jenis Kelamin & Alamat --}}
                    <div class="col-md-6">
                        <label for="kar_jekel" class="form-label fw-semibold">Jenis Kelamin</label>
                        <select class="form-select" id="kar_jekel" name="kar_jekel">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('kar_jekel', $karyawan->kar_jekel) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('kar_jekel', $karyawan->kar_jekel) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="kar_alamat" class="form-label fw-semibold">Alamat</label>
                        <input type="text" class="form-control" id="kar_alamat" name="kar_alamat"
                               value="{{ old('kar_alamat', $karyawan->kar_alamat) }}">
                    </div>

                    {{-- Email & HP --}}
                    <div class="col-md-6">
                        <label for="kar_email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="kar_email" name="kar_email"
                               value="{{ old('kar_email', $karyawan->kar_email) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_email_perusahaan" class="form-label fw-semibold">Email Perusahaan</label>
                        <input type="email" class="form-control" id="kar_email_perusahaan" name="kar_email_perusahaan"
                               value="{{ old('kar_email_perusahaan', $karyawan->kar_email_perusahaan) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_hp" class="form-label fw-semibold">No HP</label>
                        <input type="text" class="form-control" id="kar_hp" name="kar_hp"
                               value="{{ old('kar_hp', $karyawan->kar_hp) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_wa" class="form-label fw-semibold">No WA</label>
                        <input type="text" class="form-control" id="kar_wa" name="kar_wa"
                               value="{{ old('kar_wa', $karyawan->kar_wa) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="kar_telegram" class="form-label fw-semibold">Telegram</label>
                        <input type="text" class="form-control" id="kar_telegram" name="kar_telegram"
                               value="{{ old('kar_telegram', $karyawan->kar_telegram) }}">
                    </div>

                    {{-- Rekening, BPJS, Jamsostek, NPWP --}}
                    <div class="col-md-6">
                        <label for="kar_norek" class="form-label fw-semibold">No Rekening</label>
                        <input type="text" class="form-control" id="kar_norek" name="kar_norek"
                               value="{{ old('kar_norek', $karyawan->kar_norek) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_nobpjs" class="form-label fw-semibold">No BPJS</label>
                        <input type="text" class="form-control" id="kar_nobpjs" name="kar_nobpjs"
                               value="{{ old('kar_nobpjs', $karyawan->kar_nobpjs) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_nojamsostek" class="form-label fw-semibold">No Jamsostek</label>
                        <input type="text" class="form-control" id="kar_nojamsostek" name="kar_nojamsostek"
                               value="{{ old('kar_nojamsostek', $karyawan->kar_nojamsostek) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="kar_npwp" class="form-label fw-semibold">NPWP</label>
                        <input type="text" class="form-control" id="kar_npwp" name="kar_npwp"
                               value="{{ old('kar_npwp', $karyawan->kar_npwp) }}">
                    </div>

                    {{-- Master Data --}}
                    @php
                        function selectOption($data, $oldKey, $valueKey, $nameKey, $karyawanValue) {
                            foreach($data ?? [] as $item) {
                                $value = is_array($item) ? $item[$valueKey] : $item->$valueKey;
                                $name = is_array($item) ? $item[$nameKey] : $item->$nameKey;
                                $selected = old($oldKey, $karyawanValue) == $value ? 'selected' : '';
                                echo "<option value='{$value}' {$selected}>{$name}</option>";
                            }
                        }
                    @endphp

                    <div class="col-md-4">
                        <label for="agama_kode" class="form-label fw-semibold">Agama</label>
                        <select class="form-select" id="agama_kode" name="agama_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($agamas, 'agama_kode', 'agama_kode', 'agama_nama', $karyawan->agama_kode) !!}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="profesi_kode" class="form-label fw-semibold">Profesi</label>
                        <select class="form-select" id="profesi_kode" name="profesi_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($profesis, 'profesi_kode', 'profesi_kode', 'profesi_nama', $karyawan->profesi_kode) !!}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="unit_kode" class="form-label fw-semibold">Unit</label>
                        <select class="form-select" id="unit_kode" name="unit_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($units, 'unit_kode', 'unit_kode', 'unit_nama', $karyawan->unit_kode) !!}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="jabatan_kode" class="form-label fw-semibold">Jabatan</label>
                        <select class="form-select" id="jabatan_kode" name="jabatan_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($jabatans, 'jabatan_kode', 'jabatan_kode', 'jabatan_nama', $karyawan->jabatan_kode) !!}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="golongan_kode" class="form-label fw-semibold">Golongan</label>
                        <select class="form-select" id="golongan_kode" name="golongan_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($golongans, 'golongan_kode', 'golongan_kode', 'golongan_nama', $karyawan->golongan_kode) !!}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="status_kode" class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="status_kode" name="status_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($statuses, 'status_kode', 'status_kode', 'status_nama', $karyawan->status_kode) !!}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="tipe_kode" class="form-label fw-semibold">Tipe</label>
                        <select class="form-select" id="tipe_kode" name="tipe_kode">
                            <option value="">-- Pilih --</option>
                            {!! selectOption($tipes, 'tipe_kode', 'tipe_kode', 'tipe_nama', $karyawan->tipe_kode) !!}
                        </select>
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary btn-rounded shadow-sm">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
