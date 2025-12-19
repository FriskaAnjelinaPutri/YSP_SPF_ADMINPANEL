@extends('layouts.app')

@section('title', 'Update Status Tiket Helpdesk')

@section('content')
<div class="container-fluid py-4">

    {{-- Custom Styles --}}
    <style>
        body { background-color: #f0fdf4; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 15px; border: none; animation: fadeInUp 0.5s ease; box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
        .card-header { border-top-left-radius: 15px; border-top-right-radius: 15px; background: linear-gradient(90deg, #16a34a, #22c55e); color: #fff; font-weight: 600; }
        .btn-rounded { border-radius: 50px; transition: all 0.3s ease; }
        .btn-rounded:hover { transform: translateY(-2px); }
        .btn-primary { background-color: #22c55e; border: none; }
        .btn-primary:hover { background-color: #16a34a; }
        .btn-secondary { background-color: #9ca3af; border: none; }
        .btn-secondary:hover { background-color: #6b7280; }
        .form-select:focus { box-shadow: 0 0 0 0.25rem rgba(34,197,94,0.25); border-color: #22c55e; }
        .alert { border-radius: 12px; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-success mb-0">
                <i class="bi bi-pencil-square me-2"></i>Update Status Tiket
            </h3>
            <small class="text-muted">Ubah status tiket helpdesk sesuai progres</small>
        </div>
        <a href="{{ route('helpdesk.index') }}" class="btn btn-secondary shadow-sm btn-rounded">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Mohon periksa kembali inputan Anda.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card">
        <div class="card-header">
            <i class="bi bi-headset me-2"></i>Tiket #{{ $ticket->id }}
        </div>

        <div class="card-body px-4 py-4">
            <form action="{{ route('helpdesk.update', $ticket->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Ticket Info --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pelapor</label>
                    <p class="form-control-plaintext">{{ $ticket->user_nama ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Dilaporkan</label>
                    <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($ticket->tanggal_dilaporkan)->format('d M Y, H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi Masalah</label>
                    <p class="form-control-plaintext bg-light p-2 rounded">{{ $ticket->deskripsi }}</p>
                </div>

                {{-- Status Update --}}
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Update Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $ticket->status) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('helpdesk.index') }}" class="btn btn-secondary btn-rounded shadow-sm px-4">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection