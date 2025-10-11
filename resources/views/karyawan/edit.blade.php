@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="bi bi-pencil-square me-2"></i>
            <h5 class="mb-0">Edit Karyawan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('karyawan.update', $karyawan['kar_kode']) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Baris 1 --}}
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="kar_kode" class="form-control" value="{{ $karyawan['kar_kode'] }}" readonly>
                            <label>Kode Karyawan</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="kar_nip" class="form-control" value="{{ $karyawan['kar_nip'] }}">
                            <label>NIP</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="kar_nik" class="form-control" value="{{ $karyawan['kar_nik'] }}">
                            <label>NIK</label>
                        </div>
                    </div>
                </div>

                {{-- Baris 2 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="kar_nama" class="form-control" value="{{ $karyawan['kar_nama'] }}" required>
                            <label>Nama</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" name="kar_email" class="form-control" value="{{ $karyawan['kar_email'] }}">
                            <label>Email</label>
                        </div>
                    </div>
                </div>

                {{-- Baris 3 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="kar_hp" class="form-control" value="{{ $karyawan['kar_hp'] }}">
                            <label>No HP</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="tipe_kode" class="form-control" value="{{ $karyawan['tipe_kode'] }}">
                            <label>Tipe</label>
                        </div>
                    </div>
                </div>

                {{-- Baris 4 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="jabatan_kode" class="form-control" value="{{ $karyawan['jabatan_kode'] }}">
                            <label>Jabatan</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="unit_kode" class="form-control" value="{{ $karyawan['unit_kode'] }}">
                            <label>Unit</label>
                        </div>
                    </div>
                </div>

                {{-- Baris 5 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="status_kode" class="form-control" value="{{ $karyawan['status_kode'] }}">
                            <label>Status</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="golongan_kode" class="form-control" value="{{ $karyawan['golongan_kode'] }}">
                            <label>Golongan</label>
                        </div>
                    </div>
                </div>

                {{-- Baris 6 --}}
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="agama_kode" class="form-control" value="{{ $karyawan['agama_kode'] }}">
                            <label>Agama</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="profesi_kode" class="form-control" value="{{ $karyawan['profesi_kode'] }}">
                            <label>Profesi</label>
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin memperbarui data ini?')">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
