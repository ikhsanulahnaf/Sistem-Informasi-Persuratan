@extends('layouts.app')

@section('title', 'Arsip Surat')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Arsip Surat</h3>
                <p class="text-gray-600 mt-1">Surat yang telah diarsipkan ({{ $surats->total() }} surat)</p>
            </div>
            <a href="{{ route('surat.index') }}"
                class="btn-primary text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:shadow-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Kelola Surat
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-xl card-shadow p-6 mb-6" data-aos="fade-up">
            <form action="{{ route('surat.arsip') }}" method="GET" class="space-y-4">
                <!-- Search Bar -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Cari Surat</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari berdasarkan nomor, perihal, pengirim, atau penerima...">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Filter Jenis -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Jenis Surat</label>
                        <select name="jenis"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Jenis</option>
                            <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        </select>
                    </div>

                    <!-- Filter Jenis Surat Keluar -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Jenis Surat Keluar</label>
                        <select name="jenis_surat_keluar"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Jenis Keluar</option>
                            <option value="edaran_rektor" {{ request('jenis_surat_keluar') === 'edaran_rektor' ? 'selected' : '' }}>Edaran Rektor</option>
                            <option value="sk_rektor" {{ request('jenis_surat_keluar') === 'sk_rektor' ? 'selected' : '' }}>SK
                                Rektor</option>
                            <option value="surat_tugas" {{ request('jenis_surat_keluar') === 'surat_tugas' ? 'selected' : '' }}>Surat Tugas</option>
                            <option value="nota_dinas" {{ request('jenis_surat_keluar') === 'nota_dinas' ? 'selected' : '' }}>
                                Nota Dinas</option>
                            <option value="surat_keluar" {{ request('jenis_surat_keluar') === 'surat_keluar' ? 'selected' : '' }}>Surat Keluar Umum</option>
                        </select>
                    </div>

                    <!-- Filter Tanggal Dari -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Filter Tanggal Sampai -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('surat.arsip') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Reset Filter
                    </a>
                    <button type="submit"
                        class="btn-primary text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:shadow-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Arsip -->
        <div class="bg-white rounded-xl card-shadow overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Perihal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengirim/Penerima</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Diarsipkan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($surats as $surat)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium">
                                    {{ $surat->nomor_surat ?? 'DRAFT' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ \Illuminate\Support\Str::limit($surat->perihal, 50) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $surat->jenis === 'masuk' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ ucfirst($surat->jenis) }}
                                    </span>
                                    @if($surat->jenis === 'keluar' && $surat->jenis_surat_keluar)
                                        <span
                                            class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ str_replace('_', ' ', ucfirst($surat->jenis_surat_keluar)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($surat->jenis === 'masuk')
                                        <div>Pengirim: {{ $surat->pengirim }}</div>
                                    @else
                                        <div>Penerima: {{ $surat->penerima }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $surat->updated_at ? $surat->updated_at->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2 flex-wrap">
                                        {{-- Lihat Detail --}}
                                        <a href="{{ route('surat.show', $surat) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium" title="Lihat Detail">
                                            Lihat
                                        </a>

                                        {{-- Tombol Disposisi (Hanya untuk Rektor pada surat masuk) --}}
                                        @if(auth()->user()->role === 'rektor' && $surat->jenis === 'masuk' && $surat->approval_status === 'archived')
                                            @if(!$surat->disposisi || $surat->disposisi->isEmpty())
                                                <a href="{{ route('disposisi.create', $surat->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-800 font-medium" title="Buat Disposisi">
                                                    Disposisi
                                                </a>
                                            @else
                                                <span class="text-green-600 font-medium" title="Sudah Didisposisi">
                                                    ✓ Didisposisi
                                                </span>
                                            @endif
                                        @endif

                                        {{-- Download File (jika ada) --}}
                                        @if($surat->file_path)
                                            <a href="{{ asset($surat->file_path) }}" target="_blank"
                                                class="text-green-600 hover:text-green-800 font-medium" title="Unduh File">
                                                Unduh
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                        <p class="text-gray-500 font-medium">Tidak ada surat di arsip</p>
                                        <p class="text-gray-400 text-sm mt-1">Coba ubah filter atau kata kunci pencarian</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($surats->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $surats->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection