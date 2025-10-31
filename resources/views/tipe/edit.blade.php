@extends('layouts.app')

@section('title', 'Edit Tipe')

@section('content')
<div class="container mx-auto px-6 py-8">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('tipe.index') }}" class="hover:text-indigo-600">Tipe</a>
            <span>/</span>
            <span class="text-gray-700">Edit Tipe</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Tipe: {{ $tipe['tipe_kode'] ?? '' }}</h1>
    </div>

    {{-- Alert Error --}}
    @if (session('error'))
        <div class="mb-4 rounded-md bg-red-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('tipe.update', $tipe['tipe_kode']) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            {{-- Kode Tipe (Read Only) --}}
            <div class="mb-6">
                <label for="tipe_kode" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Tipe
                </label>
                <input type="text"
                       name="tipe_kode"
                       id="tipe_kode"
                       value="{{ $tipe['tipe_kode'] }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                       readonly>
                <p class="mt-1 text-xs text-gray-500">Kode tidak dapat diubah</p>
            </div>

            {{-- Nama Tipe --}}
            <div class="mb-6">
                <label for="tipe_nama" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Tipe <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="tipe_nama"
                       id="tipe_nama"
                       value="{{ old('tipe_nama', $tipe['tipe_nama']) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tipe_nama') border-red-500 @enderror"
                       placeholder="Contoh: Shift Pagi"
                       maxlength="100"
                       required>
                @error('tipe_nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Maksimal 100 karakter</p>
            </div>

            {{-- Status Aktif --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio"
                               name="tipe_aktif"
                               value="1"
                               {{ old('tipe_aktif', $tipe['tipe_aktif']) == 1 ? 'checked' : '' }}
                               class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio"
                               name="tipe_aktif"
                               value="0"
                               {{ old('tipe_aktif', $tipe['tipe_aktif']) == 0 ? 'checked' : '' }}
                               class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Nonaktif</span>
                    </label>
                </div>
                @error('tipe_aktif')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info Tambahan --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Informasi</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Dibuat:</span>
                        <span class="text-gray-700 ml-2">
                            {{ \Carbon\Carbon::parse($tipe['created_at'])->format('d M Y H:i') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500">Terakhir Diubah:</span>
                        <span class="text-gray-700 ml-2">
                            {{ \Carbon\Carbon::parse($tipe['updated_at'])->format('d M Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('tipe.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
