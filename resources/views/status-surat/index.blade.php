@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Status Surat</h1>
        <nav class="flex text-sm font-medium text-gray-500 mt-1" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                </li>
                <li>
                    <span class="mx-2">/</span>
                </li>
                <li aria-current="page" class="text-gray-800">Status Surat</li>
            </ol>
        </nav>
    </div>

    <!-- Table Card -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h5 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <!-- Icon Table -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Daftar Status Pengajuan Surat
            </h5>
        </div>
        
        <div class="p-6 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-16 text-center">No</th>
                        <th scope="col" class="px-6 py-3">Perihal</th>
                        <th scope="col" class="px-6 py-3 w-48">Tanggal Pengajuan</th>
                        <th scope="col" class="px-6 py-3 w-48 text-center">Status Terkini</th>
                        <th scope="col" class="px-6 py-3 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($surats as $surat)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-gray-900">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900 font-semibold mb-1">{{ $surat->perihal }}</div>
                                <div class="text-xs text-gray-500">
                                    Penerima: {{ $surat->penerima }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($surat->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusLabels = [
                                        'pending_wr' => 'Menunggu WR',
                                        'approved_wr' => 'Disetujui WR',
                                        'rejected_wr' => 'Revisi WR',
                                        'waiting_rektor_approval' => 'Menunggu Rektor',
                                        'approved_rektor' => 'Disetujui Rektor',
                                        'rejected_rektor' => 'Revisi Rektor',
                                        'signed_rektor' => 'TTD Rektor',
                                        'numbered' => 'Bernomor',
                                        'archived' => 'Arsip',
                                        'returned' => 'Selesai',
                                    ];
                                    
                                    // Tailwind Colors for Badges
                                    $badgeClasses = match($surat->approval_status) {
                                        'pending_wr', 'waiting_rektor_approval' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'approved_wr', 'approved_rektor', 'signed_rektor', 'numbered' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'rejected_wr', 'rejected_rektor' => 'bg-red-100 text-red-800 border-red-200',
                                        'archived', 'returned' => 'bg-green-100 text-green-800 border-green-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                    {{ $statusLabels[$surat->approval_status] ?? $surat->approval_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('status-surat.show', $surat->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors shadow-sm gap-1">
                                    <!-- Eye Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-base font-medium">Belum ada pengajuan surat.</p>
                                    <p class="text-sm mt-1">Silakan buat surat baru melalui menu "Buat Surat Baru".</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-4 px-4 pb-2">
                {{ $surats->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
