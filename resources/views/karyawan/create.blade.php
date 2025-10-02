@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Tambah Karyawan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('karyawan.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="kar_kode" class="form-control" placeholder="Kode" required>
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

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="kar_nama" class="form-control" placeholder="Nama" required>
                            <label>Nama</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" name="kar_email" class="form-control" placeholder="Email">
                            <label>Email</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="kar_hp" class="form-control" placeholder="HP">
                            <label>No HP</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="tipe_kode" class="form-control" placeholder="Tipe">
                            <label>Tipe</label>
                        </div>
                    </div>
                </div>

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
                            <label>Unit</label>
                        </div>
                    </div>
                </div>

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

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="agama_kode" class="form-control" placeholder="Agama">
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

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
