@extends('layouts.app')

@section('title', 'Edit Pola')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Edit Pola</h1>

    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('pola.update', $pola['pola_kode']) }}" method="POST" class="bg-white shadow-md rounded p-6">
        @csrf
        @method('PUT')

        {{-- Pilih Tipe --}}
        <div class="mb-4">
            <label for="tipe_kode" class="block font-medium text-gray-700">Tipe</label>
            <select name="tipe_kode" id="tipe_kode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Pilih Tipe --</option>
                @foreach ($tipes as $tipe)
                    <option value="{{ $tipe['tipe_kode'] }}" {{ $pola['tipe_kode'] == $tipe['tipe_kode'] ? 'selected' : '' }}>
                        {{ $tipe['tipe_nama'] }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Jadwal --}}
        <div class="mb-4">
            <label for="jadwal_kode" class="block font-medium text-gray-700">Jadwal</label>
            <select name="jadwal_kode" id="jadwal_kode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Pilih Jadwal --</option>
                @foreach ($jadwals as $jadwal)
                    <option value="{{ $jadwal['jadwal_kode'] }}" {{ $pola['jadwal_kode'] == $jadwal['jadwal_kode'] ? 'selected' : '' }}>
                        {{ $jadwal['jadwal_nama'] ?? $jadwal['jadwal_kode'] }}
                        ({{ $jadwal['jam_mulai'] }} - {{ $jadwal['jam_selesai'] }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Urutan --}}
        <div class="mb-4">
            <label for="urut" class="block font-medium text-gray-700">Urutan</label>
            <input type="number" name="urut" id="urut" value="{{ $pola['urut'] }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end space-x-2">
            <a href="{{ route('pola.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Kembali
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
