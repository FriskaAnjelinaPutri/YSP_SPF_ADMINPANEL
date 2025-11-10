@extends('layouts.app')

@section('title', 'Edit Cuti')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-success text-white fw-semibold">
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
                        <a href="{{ route('cuti.index') }}" class="btn btn-outline-secondary btn-rounded">
                            <i class="bi bi-arrow-left-circle me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success btn-rounded">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: 20px;
            transition: all 0.3s ease;
            background: #fff;
            border: none;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            border-top-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
            background: linear-gradient(90deg, #16a34a, #22c55e);
        }

        .btn-rounded {
            border-radius: 50px;
            transition: transform 0.2s ease;
        }

        .btn-rounded:hover {
            transform: scale(1.05);
        }

        .btn-success {
            background-color: #22c55e;
            border: none;
        }

        .btn-success:hover {
            background-color: #16a34a;
        }

        .btn-outline-secondary {
            color: #166534;
            border-color: #166534;
        }

        .btn-outline-secondary:hover {
            background-color: #dcfce7;
            color: #166534;
        }

        label {
            font-weight: 500;
            color: #14532d;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border-color: #d1fae5;
            transition: border-color 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.25);
        }
    </style>
@endsection
