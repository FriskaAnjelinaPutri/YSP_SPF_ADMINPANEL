@extends('layouts.app')

@section('title', 'Attendance - Admin Panel')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Attendance (Periode: {{ \Carbon\Carbon::parse($periode.'-01')->format('F Y') }})</h4>

    {{-- Filter Bulan & Departemen --}}
    <form method="GET" action="{{ route('absensi.index') }}" class="mb-3 d-flex gap-2 align-items-center flex-wrap">
        <input type="month" name="periode" value="{{ $periode }}" class="form-control" style="max-width: 200px;">

        <select name="department" class="form-select" style="max-width: 200px;">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    @if(!empty($absensiData) && count($absensiData) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Tanggal</th>
                        <th>Check In</th>
                        <th>Lokasi In</th>
                        <th>Check Out</th>
                        <th>Lokasi Out</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensiData as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['nama_karyawan'] ?? '-' }}</td>
                            <td>{{ $item['department'] ?? '-' }}</td>
                            <td>{{ $item['tanggal'] ?? '-' }}</td>
                            <td>{{ $item['check_in'] ?? '-' }}</td>
                            <td>{{ $item['lokasi_check_in'] ?? '-' }}</td>
                            <td>{{ $item['check_out'] ?? '-' }}</td>
                            <td>{{ $item['lokasi_check_out'] ?? '-' }}</td>
                            <td>
                                @if(($item['status'] ?? '') === 'Hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif(($item['status'] ?? '') === 'Tidak Hadir')
                                    <span class="badge bg-danger">Tidak Hadir</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item['status'] ?? '-' }}</span>
                                @endif
                            </td>
                            <td>{{ $item['keterangan'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center text-muted mt-4">
            <i class="bi bi-emoji-frown" style="font-size: 2rem;"></i>
            <p class="mt-2">Tidak ada data absensi untuk periode ini.</p>
        </div>
    @endif
</div>
@endsection
