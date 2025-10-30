@extends('layouts.app')

@section('title', 'Manajemen Pola Kerja')

@section('content')
<div class="container mx-auto px-6 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Pola Kerja</h1>
            <p class="text-gray-500 text-sm">Kelola pola kerja berdasarkan tipe dan jadwal</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('pola.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pola
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-7h2v2H9v-2zm0-4h2v3H9V7z"
                              clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-md bg-red-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-9 4h2v2H9v-2zm0-8h2v6H9V6z"
                              clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-4">
            <table id="polaTable" class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-100 text-gray-800 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Kode Pola</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Jadwal</th>
                        <th class="px-4 py-3">Urutan</th>
                        <th class="px-4 py-3">Dibuat</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($polas as $index => $pola)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $pola['pola_kode'] }}</td>
                            <td class="px-4 py-3">{{ $pola['tipe']['tipe_nama'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $pola['jadwal']['jadwal_nama'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">{{ $pola['urut'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500 text-sm">
                                {{ \Carbon\Carbon::parse($pola['created_at'])->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('pola.edit', $pola['pola_kode']) }}"
                                       class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5h2m-1 0v14m7-7H5" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('pola.destroy', $pola['pola_kode']) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-6">
                                Tidak ada data pola kerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.jQuery && $.fn.DataTable) {
            $('#polaTable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ditemukan data yang cocok",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });
        }
    });
</script>
@endsection
