@extends('layouts.app')

@section('content')
<h1>Daftar Jadwal</h1>

<a href="{{ route('jadwal.create') }}" class="btn btn-primary mb-3">Tambah Jadwal</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($jadwals as $jadwal)
        <tr>
            <td>{{ $jadwal['jadwal_kode'] }}</td>
            <td>{{ $jadwal['jadwal_nama'] }}</td>
            <td>{{ $jadwal['jam_mulai'] }}</td>
            <td>{{ $jadwal['jam_selesai'] }}</td>
            <td>{{ $jadwal['status'] }}</td>
            <td>
                <a href="{{ route('jadwal.edit', $jadwal['jadwal_kode']) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('jadwal.destroy', $jadwal['jadwal_kode']) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus jadwal ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6">Tidak ada data jadwal</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
