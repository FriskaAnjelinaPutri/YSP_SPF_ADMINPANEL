@extends('layouts.app')

@section('title', 'Edit Cuti')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-primary text-white fw-semibold">
                <i class="bi bi-pencil-square me-2"></i>Edit Data Cuti
            </div>
            <div class="card-body">
                <form action="{{ route('cuti.update', $cuti['id']) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kar_kode" class="form-label">Karyawan</label>
                            <select name="kar_kode" id="kar_kode" class="form-select" disabled>
                                @foreach ($karyawans as $k)
                                    <option value="{{ $k['kar_kode'] }}"
                                        {{ $cuti['kar_kode'] == $k['kar_kode'] ? 'selected' : '' }}>
                                        {{ $k['kar_nama'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai"
                                value="{{ $cuti['tanggal_mulai'] }}" required>
                        </div>

                        <div class="col-md-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai"
                                value="{{ $cuti['tanggal_selesai'] }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="jenis_cuti" class="form-label">Jenis Cuti</label>
                            <select name="jenis_cuti" id="jenis_cuti" class="form-select" required>
                                @foreach (['Tahunan', 'Sakit', 'Melahirkan', 'Menikah', 'Keluarga Meninggal', 'Lainnya'] as $jenis)
                                    <option value="{{ $jenis }}"
                                        {{ $cuti['jenis_cuti'] == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select"
                                @if ($cuti['status'] === 'Rejected') disabled @endif required>
                                @foreach (['Pending', 'Approved', 'Rejected'] as $s)
                                    <option value="{{ $s }}" {{ $cuti['status'] == $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-12">
                            <label for="alasan" class="form-label">Alasan</label>
                            <textarea name="alasan" id="alasan" rows="3" class="form-control" required>{{ $cuti['alasan'] }}</textarea>
                        </div>

                        <div class="col-12">
                            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control">{{ $cuti['keterangan'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('cuti.index') }}" class="btn btn-secondary btn-rounded">
                            <i class="bi bi-arrow-left-circle me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-rounded">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
