@extends('layouts.app')

@section('title', 'Manajemen Tipe')

@section('content')
<div class="container mx-auto px-6 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Tipe</h1>
            <p class="text-gray-500 text-sm">Kelola tipe pola kerja karyawan</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('tipe.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4" />
                </svg>
                Tambah Tipe
            </a>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-100 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

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

    {{-- Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-4">
            <table id="tipeTable" class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-100 text-gray-800 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Kode Tipe</th>
                        <th class="px-4 py-3">Nama Tipe</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Dibuat</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tipes as $index => $tipe)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $tipe['tipe_kode'] }}</td>
                            <td class="px-4 py-3">{{ $tipe['tipe_nama'] }}</td>
                            <td class="px-4 py-3">
                                @if($tipe['tipe_aktif'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-sm">
                                {{ \Carbon\Carbon::parse($tipe['created_at'])->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('tipe.edit', $tipe['tipe_kode']) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('tipe.destroy', $tipe['tipe_kode']) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus tipe {{ $tipe['tipe_nama'] }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-2">Tidak ada data tipe kerja.</p>
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
            $('#tipeTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[1, 'asc']], // Sort by kode
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ditemukan data yang cocok",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
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
