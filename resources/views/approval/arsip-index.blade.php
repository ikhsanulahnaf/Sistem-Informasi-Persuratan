@extends('layouts.app')

@section('title', 'Arsip Surat')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Arsip Surat</h1>
                <p class="text-gray-600 mt-1">Kelola dan lihat semua surat yang telah diarsipkan</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white rounded-lg card-shadow p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filter Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Arsip</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="retensi" {{ request('status') === 'retensi' ? 'selected' : '' }}>Retensi</option>
                        <option value="pemusnahan" {{ request('status') === 'pemusnahan' ? 'selected' : '' }}>Pemusnahan</option>
                    </select>
                </div>

                <!-- Filter Jenis Surat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat</label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Jenis</option>
                        <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Nomor/Perihal</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Button -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>
                    <a href="{{ route('') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Arsip</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalArsip }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7m-6 4h.01M9 11h.01M15 11h.01M9 15h.01M15 15h.01" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Surat Keluar</p>
                        <p class="text-3xl font-bold text-green-600">{{ $suratKeluar }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8H8m4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg card-shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Surat Masuk</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $suratMasuk }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Arsip -->
        <div class="bg-white rounded-lg card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nomor Surat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Perihal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal Arsip</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($arsips as $index => $arsip)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="font-semibold text-gray-900">{{ $arsip->nomor_surat }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($arsip->surat->perihal ?? '-', 40) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($arsip->surat->jenis === 'keluar')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Keluar</span>
                                    @else
                                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">Masuk</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $arsip->tanggal_arsip ? $arsip->tanggal_arsip->format('d M Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($arsip->status === 'aktif')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">✓ Aktif</span>
                                    @elseif($arsip->status === 'retensi')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">⏱ Retensi</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">✕ Pemusnahan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('surat.show', $arsip->surat->id) }}" 
                                            class="text-blue-600 hover:text-blue-800 transition" title="Lihat">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('surat.download', $arsip->surat->id) }}" 
                                            class="text-green-600 hover:text-green-800 transition" title="Download">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-600">Tidak ada arsip surat yang ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($arsips->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $arsips->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
