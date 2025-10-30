@extends('layouts.app')

@section('content')
<h1>Tambah Jadwal</h1>

<form action="{{ route('jadwal.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Jadwal</label>
        <input type="text" name="jadwal_nama" class="form-control">
    </div>
    <div class="mb-3">
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="1">Aktif</option>
            <option value="0">Non-Aktif</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@endsection
