@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
    <div class="container mx-auto px-6 py-8">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('jadwal.index') }}" class="hover:text-indigo-600 transition">Jadwal</a>
                <span>/</span>
                <span class="text-gray-700">Edit Jadwal</span>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Jadwal</h1>
                    <p class="text-gray-500 text-sm mt-1">Perbarui informasi jadwal kerja</p>
                </div>
                <div class="text-right">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Kode: {{ $jadwal->jadwal_kode ?? '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Jadwal</h2>
            </div>

            <form action="{{ route('jadwal.update', $jadwal->jadwal_kode) }}" method="POST" class="p-6" id="jadwalForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kode Jadwal (Read Only) --}}
                    <div class="md:col-span-2">
                        <label for="jadwal_kode" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Jadwal
                        </label>
                        <input type="text" name="jadwal_kode" id="jadwal_kode" value="{{ $jadwal->jadwal_kode }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                            readonly>
                        <p class="mt-1 text-xs text-gray-500">Kode tidak dapat diubah</p>
                    </div>

                    {{-- Nama Jadwal --}}
                    <div class="md:col-span-2">
                        <label for="jadwal_nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Jadwal <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jadwal_nama" id="jadwal_nama"
                            value="{{ old('jadwal_nama', $jadwal->jadwal_nama) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('jadwal_nama') border-red-500 ring-2 ring-red-200 @enderror"
                            placeholder="Contoh: Shift Pagi" maxlength="100" required>
                        @error('jadwal_nama')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Nama jadwal kerja, maksimal 100 karakter</p>
                    </div>

                    {{-- Jam Mulai --}}
                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Mulai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input type="time" name="jam_mulai" id="jam_mulai"
                                value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('jam_mulai') border-red-500 ring-2 ring-red-200 @enderror"
                                required>
                        </div>
                        @error('jam_mulai')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Waktu mulai kerja</p>
                    </div>

                    {{-- Jam Selesai --}}
                    <div>
                        <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Selesai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input type="time" name="jam_selesai" id="jam_selesai"
                                value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('jam_selesai') border-red-500 ring-2 ring-red-200 @enderror"
                                required>
                        </div>
                        @error('jam_selesai')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Waktu selesai kerja</p>
                    </div>


                    {{-- Durasi (Auto Calculate) --}}
                    <div class="md:col-span-2">
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-indigo-900">Durasi Kerja</p>
                                    <p class="text-sm text-indigo-700" id="durasi">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-6">
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="radio" name="status" value="1"
                                    {{ old('status', $jadwal->status) == '1' ? 'checked' : '' }}
                                    class="form-radio h-5 w-5 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <span
                                    class="ml-2 text-sm text-gray-700 group-hover:text-indigo-600 transition">Aktif</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="radio" name="status" value="0"
                                    {{ old('status', $jadwal->status) == '0' ? 'checked' : '' }}
                                    class="form-radio h-5 w-5 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                                <span
                                    class="ml-2 text-sm text-gray-700 group-hover:text-indigo-600 transition">Nonaktif</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Info Tambahan --}}
                    <div class="md:col-span-2">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi Tambahan
                            </h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Dibuat:</span>
                                    <span class="text-gray-700 ml-2 font-medium">
                                        {{ \Carbon\Carbon::parse($jadwal->created_at)->format('d M Y H:i') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Terakhir Diubah:</span>
                                    <span class="text-gray-700 ml-2 font-medium">
                                        {{ \Carbon\Carbon::parse($jadwal->updated_at)->format('d M Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-gray-200">
                    <a href="{{ route('jadwal.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-150 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition duration-150 flex items-center shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Perbarui Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

@section('scripts')
    <script>
        // Calculate duration
        function calculateDuration() {
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;
            const durasiEl = document.getElementById('durasi');

            if (jamMulai && jamSelesai) {
                const start = new Date('2000-01-01 ' + jamMulai);
                let end = new Date('2000-01-01 ' + jamSelesai);

                // Jika jam selesai lebih kecil dari jam mulai, berarti melewati tengah malam
                if (end < start) {
                    end.setDate(end.getDate() + 1);
                }

                const diff = (end - start) / 1000 / 60; // dalam menit
                const hours = Math.floor(diff / 60);
                const minutes = diff % 60;

                if (hours >= 0) {
                    durasiEl.textContent = `${hours} jam ${minutes} menit`;
                    durasiEl.classList.remove('text-red-700');
                    durasiEl.classList.add('text-indigo-700');
                } else {
                    durasiEl.textContent = 'Jam selesai tidak boleh kurang dari jam mulai';
                    durasiEl.classList.remove('text-indigo-700');
                    durasiEl.classList.add('text-red-700');
                }
            } else {
                durasiEl.textContent = '-';
            }
        }

        document.getElementById('jam_mulai').addEventListener('change', calculateDuration);
        document.getElementById('jam_selesai').addEventListener('change', calculateDuration);

        // Auto calculate on page load
        document.addEventListener('DOMContentLoaded', calculateDuration);

        // Form validation
        document.getElementById('jadwalForm').addEventListener('submit', function(e) {
            const jamMulai = document.getElementById('jam_mulai').value;
            const jamSelesai = document.getElementById('jam_selesai').value;
            const jadwalNama = document.getElementById('jadwal_nama').value.trim();

            if (!jadwalNama) {
                e.preventDefault();
                alert('Nama jadwal wajib diisi');
                document.getElementById('jadwal_nama').focus();
                return false;
            }

            if (!jamMulai || !jamSelesai) {
                e.preventDefault();
                alert('Jam mulai dan jam selesai wajib diisi');
                return false;
            }
        });
    </script>
@endsection
@endsection
