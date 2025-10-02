@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Karyawan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('karyawan.update', $karyawan['kar_kode']) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Kode Karyawan</label>
                        <input type="text" name="kar_kode" class="form-control"
                               value="{{ $karyawan['kar_kode'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>NIP</label>
                        <input type="text" name="kar_nip" class="form-control"
                               value="{{ $karyawan['kar_nip'] }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>NIK</label>
                        <input type="text" name="kar_nik" class="form-control"
                               value="{{ $karyawan['kar_nik'] }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Nama</label>
                        <input type="text" name="kar_nama" class="form-control"
                               value="{{ $karyawan['kar_nama'] }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="kar_email" class="form-control"
                               value="{{ $karyawan['kar_email'] }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>No HP</label>
                        <input type="text" name="kar_hp" class="form-control"
                               value="{{ $karyawan['kar_hp'] }}">
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Tipe</label>
                        <input type="text" name="tipe_kode" class="form-control"
                               value="{{ $karyawan['tipe_kode'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan_kode" class="form-control"
                               value="{{ $karyawan['jabatan_kode'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Unit</label>
                        <input type="text" name="unit_kode" class="form-control"
                               value="{{ $karyawan['unit_kode'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Status</label>
                        <input type="text" name="status_kode" class="form-control"
                               value="{{ $karyawan['status_kode'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Golongan</label>
                        <input type="text" name="golongan_kode" class="form-control"
                               value="{{ $karyawan['golongan_kode'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Agama</label>
                        <input type="text" name="agama_kode" class="form-control"
                               value="{{ $karyawan['agama_kode'] }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Profesi</label>
                        <input type="text" name="profesi_kode" class="form-control"
                               value="{{ $karyawan['profesi_kode'] }}">
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
