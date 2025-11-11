@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Hasil Generate Jadwal Kerja</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ $error }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form action="{{ route('jadwal.hasil') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-grow">
                <label for="bulan" class="block text-gray-700 text-sm font-bold mb-2">Bulan:</label>
                <select name="bulan" id="bulan" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ (int)$bulan === $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="flex-grow">
                <label for="tahun" class="block text-gray-700 text-sm font-bold mb-2">Tahun:</label>
                <select name="tahun" id="tahun" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++)
                        <option value="{{ $i }}" {{ (int)$tahun === $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Filter
                </button>
            </div>
        </form>
    </div>

    @if ($paginator && $paginator->total() > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Karyawan
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jadwal
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jam Mulai
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jam Selesai
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paginator->items() as $jadwalKerja)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $jadwalKerja['karyawan_nama'] ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ \Carbon\Carbon::parse($jadwalKerja['tanggal'])->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $jadwalKerja['jadwal_nama'] ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $jadwalKerja['jam_mulai'] ?? 'N/A' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $jadwalKerja['jam_selesai'] ?? 'N/A' }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $paginator->appends(request()->except('page'))->links() }}
            </div>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg p-6">
            <p class="text-gray-600">Tidak ada jadwal kerja yang ditemukan untuk bulan dan tahun ini.</p>
        </div>
    @endif
</div>
@endsection
