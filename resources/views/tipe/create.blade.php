@extends('layouts.app')

@section('title', 'Tambah Tipe')

@section('content')
<div class="container mx-auto px-6 py-8">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('tipe.index') }}" class="hover:text-indigo-600">Tipe</a>
            <span>/</span>
            <span class="text-gray-700">Tambah Tipe</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Tipe Baru</h1>
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
        <form action="{{ route('tipe.store') }}" method="POST" class="p-6">
            @csrf

            {{-- Info Auto Generate --}}
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Kode tipe akan digenerate otomatis</strong> oleh sistem setelah data disimpan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Nama Tipe --}}
            <div class="mb-6">
                <label for="tipe_nama" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Tipe <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="tipe_nama"
                       id="tipe_nama"
                       value="{{ old('tipe_nama') }}"
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
                               {{ old('tipe_aktif', '1') == '1' ? 'checked' : '' }}
                               class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio"
                               name="tipe_aktif"
                               value="0"
                               {{ old('tipe_aktif') == '0' ? 'checked' : '' }}
                               class="form-radio h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Nonaktif</span>
                    </label>
                </div>
                @error('tipe_aktif')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('tipe.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
