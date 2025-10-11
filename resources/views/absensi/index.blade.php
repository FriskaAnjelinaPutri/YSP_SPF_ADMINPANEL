@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Attendance (Periode: {{ $periode }})</h4>

    <form method="GET" action="{{ route('absensi.index') }}" class="mb-3 d-flex gap-2">
        <input type="month" name="periode" value="{{ $periode }}" class="form-control" style="max-width: 200px;">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    @if(!empty($absensiData))
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensiData as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['nama_karyawan'] ?? '-' }}</td>
                            <td>{{ $item['tanggal'] ?? '-' }}</td>
                            <td>{{ $item['check_in'] ?? '-' }}</td>
                            <td>{{ $item['check_out'] ?? '-' }}</td>
                            <td>{{ $item['status'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">Tidak ada data absensi untuk periode ini.</p>
    @endif
</div>
@endsection
