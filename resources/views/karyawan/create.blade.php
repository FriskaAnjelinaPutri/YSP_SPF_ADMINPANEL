@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-gradient text-white d-flex align-items-center justify-content-between"
             style="background: linear-gradient(90deg, #007bff, #00b4d8);">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Karyawan</h5>
            <a href="{{ route('karyawan.index') }}" class="btn btn-light btn-sm text-primary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card-body bg-light">
            {{-- Alert --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="bi bi-exclamation-circle-fill me-2"></i>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('karyawan.store') }}" method="POST" class="mt-3">
                @csrf

                {{-- Row 1 --}}
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="kar_kode" class="form-control border-primary" placeholder="Kode" required>
                            <label>Kode Karyawan</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="kar_nip" class="form-control" placeholder="NIP">
                            <label>NIP</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="kar_nik" class="form-control" placeholder="NIK">
                            <label>NIK</label>
                        </div>
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="kar_nama" class="form-control" placeholder="Nama" required>
                            <label>Nama Lengkap</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" name="kar_email" class="form-control" placeholder="Email">
                            <label>Email</label>
                        </div>
                    </div>
                </div>

                {{-- Row 3 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="kar_hp" class="form-control" placeholder="No HP">
                            <label>No HP</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="tipe_kode" class="form-select">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="TETAP">Pegawai Tetap</option>
                                <option value="KONTRAK">Pegawai Kontrak</option>
                                <option value="MAGANG">Magang</option>
                            </select>
                            <label>Tipe Karyawan</label>
                        </div>
                    </div>
                </div>

                {{-- Row 4 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="jabatan_kode" class="form-control" placeholder="Jabatan">
                            <label>Jabatan</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="unit_kode" class="form-control" placeholder="Unit">
                            <label>Unit / Departemen</label>
                        </div>
                    </div>
                </div>

                {{-- Row 5 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="status_kode" class="form-control" placeholder="Status">
                            <label>Status</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="golongan_kode" class="form-control" placeholder="Golongan">
                            <label>Golongan</label>
                        </div>
                    </div>
                </div>

                {{-- Row 6 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="agama_kode" class="form-select">
                                <option value="">-- Pilih Agama --</option>
                                <option value="ISLAM">Islam</option>
                                <option value="KRISTEN">Kristen</option>
                                <option value="KATOLIK">Katolik</option>
                                <option value="HINDU">Hindu</option>
                                <option value="BUDDHA">Buddha</option>
                                <option value="KONGHUCU">Konghucu</option>
                            </select>
                            <label>Agama</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="profesi_kode" class="form-control" placeholder="Profesi">
                            <label>Profesi</label>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="bi bi-save2 me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
