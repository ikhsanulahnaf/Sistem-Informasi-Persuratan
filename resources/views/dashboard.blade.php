@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card Total Surat -->
        <div class="bg-white p-6 rounded-xl card-shadow" data-aos="flip-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Surat</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSurat }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Surat Pending -->
        <div class="bg-white p-6 rounded-xl card-shadow" data-aos="flip-left" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Surat Pending</p>
                    <p class="text-2xl font-bold text-orange-500">{{ $pendingCount }}</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card Total Pengguna -->
        @if(auth()->user()->role === 'admin')
            <div class="bg-white p-6 rounded-xl card-shadow" data-aos="flip-left" data-aos-delay="200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pengguna</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $totalUser }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        <!-- Card Role Aktif -->
        <div class="bg-white p-6 rounded-xl card-shadow" data-aos="flip-left" data-aos-delay="300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Role Anda</p>
                    <p class="text-2xl font-bold capitalize text-green-600">
    @if(auth()->user()->role === 'wakil_rektor')
        Wakil Rektor
    @else
        {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
    @endif
</p>

                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Surat -->
    <div class="bg-white rounded-xl card-shadow p-6" data-aos="fade-up">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-gray-900">Surat Terbaru</h3>
            <a href="{{ route('surat.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Lihat Semua
                →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 text-sm border-b">
                        <th class="pb-3">Nomor</th>
                        <th class="pb-3">Perihal</th>
                        <th class="pb-3">Tanggal</th>
                        <th class="pb-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $surat)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-3 font-mono text-sm">{{ $surat->nomor_surat ?? '-' }}</td>
                            <td class="py-3">
                                @if($surat->jenis === 'keluar' && $surat->jenis_surat_keluar === 'surat_keluar')
                                    Surat Keluar Umum
                                @else
                                    {{ Str::limit($surat->perihal, 30) }}
                                @endif
                            </td>
                            <td class="py-3 text-gray-600">
                                @if($surat->jenis === 'keluar' && $surat->jenis_surat_keluar === 'surat_keluar')
                                    -
                                @else
                                    {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d M Y') : '-' }}
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    @if(in_array($surat->approval_status, ['archived', 'returned']))
                                                        bg-green-100 text-green-800
                                                    @elseif(in_array($surat->approval_status, ['pending_wr', 'rejected_wr']))
                                                        bg-yellow-100 text-yellow-800
                                                    @else
                                                        bg-blue-100 text-blue-800
                                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $surat->approval_status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500">Tidak ada surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection