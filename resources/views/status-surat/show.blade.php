@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Status Surat</h1>
        <nav class="flex text-sm font-medium text-gray-500 mt-1" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li>
                    <a href="{{ route('status-surat.index') }}" class="hover:text-blue-600">Status Surat</a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li aria-current="page" class="text-gray-800">Detail</li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Informasi Surat -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informasi Surat
                    </h5>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        <!-- Jenis Surat -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Jenis Surat</label>
                            @if($surat->jenis == 'keluar')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Surat Keluar
                                </span>
                                <p class="text-sm text-gray-600 mt-1 capitalize">{{ str_replace('_', ' ', $surat->jenis_surat_keluar) }}</p>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                    Surat Masuk
                                </span>
                            @endif
                        </div>

                        <!-- Perihal -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Perihal</label>
                            <p class="text-gray-900 font-medium">{{ $surat->perihal }}</p>
                        </div>

                        <!-- Penerima -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tujuan / Penerima</label>
                            <div class="flex items-center gap-2">
                                <span class="bg-gray-100 p-1.5 rounded-md text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </span>
                                <span class="text-gray-900">{{ $surat->penerima }}</span>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tanggal Diajukan</label>
                            <div class="flex items-center gap-2">
                                <span class="bg-gray-100 p-1.5 rounded-md text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <span class="text-gray-900">{{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <!-- Nomor Surat (jika ada) -->
                        @if($surat->nomor_surat)
                        <div class="pt-2 border-t border-gray-100">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nomor Surat</label>
                            <p class="text-lg font-bold text-gray-800 bg-gray-50 p-2 rounded border border-gray-200 text-center font-mono">
                                {{ $surat->nomor_surat }}
                            </p>
                        </div>
                        @endif

                        <!-- File Action -->
                        <div class="pt-4 mt-2 border-t border-gray-100">
                             @if($surat->jenis_surat_keluar == 'surat_keluar' && $surat->file_path)
                                 <a href="{{ asset($surat->file_path) }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Lihat File Surat
                                </a>
                            @elseif($surat->jenis == 'keluar')
                                <a href="{{ route('surat.preview', $surat->id) }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Preview Draft
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Timeline -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden h-full">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Perjalanan Surat
                    </h5>
                </div>
                <div class="p-6">
                    @if($statusHistory->count() > 0)
                        <div class="relative pl-4 border-l-2 border-gray-200 ml-3 space-y-8">
                            @foreach($statusHistory as $history)
                                @php
                                    $isRejection = str_contains($history->status_baru, 'rejected');
                                    $isSuccess = in_array($history->status_baru, ['archived', 'returned', 'numbered']);
                                    
                                    $dotClass = $isRejection ? 'bg-red-500 ring-red-200' : ($isSuccess ? 'bg-green-500 ring-green-200' : 'bg-blue-500 ring-blue-200');
                                @endphp
                                <div class="relative">
                                    <!-- Dot -->
                                    <div class="absolute -left-[21px] top-1 h-4 w-4 rounded-full border-2 border-white ring-4 {{ $dotClass }}"></div>
                                    
                                    <!-- Content -->
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:shadow-sm transition-shadow">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                                            <div>
                                                <h3 class="text-base font-bold text-gray-900">
                                                    @php
                                                        $statusText = match($history->status_baru) {
                                                            'pending_wr' => 'Diajukan ke Wakil Rektor',
                                                            'approved_wr' => 'Disetujui Wakil Rektor',
                                                            'rejected_wr' => 'Ditolak Wakil Rektor',
                                                            'waiting_rektor_approval' => 'Menunggu Approval Rektor',
                                                            'approved_rektor' => 'Disetujui Rektor',
                                                            'rejected_rektor' => 'Ditolak Rektor',
                                                            'signed_rektor' => 'Ditandatangani Rektor',
                                                            'numbered' => 'Diberi Nomor oleh Admin',
                                                            'archived' => 'Diarsipkan',
                                                            'returned' => 'Dikembalikan ke Departemen',
                                                            default => ucwords(str_replace('_', ' ', $history->status_baru))
                                                        };
                                                    @endphp
                                                    {{ $statusText }}
                                                </h3>
                                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    Oleh: <span class="font-medium text-gray-700">{{ $history->user->name ?? 'System' }}</span>
                                                </p>
                                            </div>
                                            <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded border border-gray-200 whitespace-nowrap mt-2 sm:mt-0">
                                                {{ \Carbon\Carbon::parse($history->created_at)->diffForHumans() }}
                                            </span>
                                        </div>

                                        <p class="text-xs text-gray-400 mb-3 block">
                                            {{ \Carbon\Carbon::parse($history->created_at)->format('l, d F Y - H:i') }} WIB
                                        </p>

                                        @if($history->catatan)
                                            <div class="mt-2 text-sm p-3 rounded-md {{ $isRejection ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-yellow-50 text-yellow-800 border border-yellow-100' }}">
                                                <strong class="block text-xs uppercase opacity-70 mb-1">Catatan:</strong>
                                                {{ $history->catatan }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
                             <div class="bg-blue-50 p-3 rounded-full mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                             </div>
                            <h3 class="text-gray-900 font-medium">Belum ada riwayat</h3>
                            <p class="text-gray-500 text-sm mt-1">Surat ini belum memiliki catatan riwayat status.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
