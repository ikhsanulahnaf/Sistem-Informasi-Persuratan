@extends('layouts.app')

@section('title', 'Detail Surat')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl card-shadow p-6" data-aos="fade-up">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $surat->perihal }}</h3>
                    <p class="text-gray-600 mt-1">Nomor: <span class="font-mono">{{ $surat->nomor_surat }}</span></p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($surat->jenis === 'masuk') bg-blue-100 text-blue-800
                                            @else bg-purple-100 text-purple-800
                                            @endif">
                    {{ ucfirst($surat->jenis) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-500 text-sm">Tanggal Surat</p>
                       <p class="font-medium">
    {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d F Y') : '-' }}
</p>

                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Pengirim</p>
                        <p class="font-medium">{{ $surat->pengirim }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Penerima</p>
                        <p class="font-medium">{{ $surat->penerima }}</p>
                    </div>
                    @if(!empty($disposisi))
                        <div>
                            <p class="text-gray-500 text-sm">Instruksi</p>
                            <p class="font-medium">{{ $disposisi->instruksi }} (Tujuan: {{ $disposisi->tujuan_disposisi }})</p>
                        </div>
                    @endif
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-500 text-sm">Dibuat Oleh</p>
                        <p class="font-medium">{{ $surat->creator->name }} ({{ ucfirst($surat->creator->role) }})</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Status</p>
                        <p class="font-medium">
                            <span class="px-2 py-1 rounded-full text-xs
                                                    @if($surat->approval_status === 'archived' || $surat->approval_status === 'returned')
                                                        bg-green-100 text-green-800
                                                    @elseif(in_array($surat->approval_status, ['pending_wr', 'rejected_wr']))
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif($surat->approval_status === 'draft' && $surat->jenis === 'masuk')
                                                        bg-blue-100 text-blue-800
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif">
                                {{ ucfirst(str_replace('_', ' ', $surat->approval_status)) }}
                            </span>
                        </p>
                    </div>
                    @if($surat->isi_ringkas)
                        <div>
                            <p class="text-gray-500 text-sm">Isi Ringkas</p>
                            <p class="font-medium">{{ $surat->isi_ringkas }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('surat.download', $surat) }}"
                    class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Unduh File
                </a>
                @if(!empty($disposisi))
                    <a href="{{ route('disposisi.pdf', $disposisi->id) }}"
                        class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Unduh Lembar Disposisi
                    </a>
                @endif
                @php
                    $canEdit = $surat->jenis === 'keluar'
                        && in_array($surat->approval_status, ['pending_wr', 'rejected_wr', 'waiting_signature'])
                        && ($surat->created_by == auth()->id() || in_array(auth()->user()->role, ['wakil_rektor', 'rektor', 'admin']));
                @endphp
                @if($canEdit)
                    <a href="{{ route('surat.edit', $surat) }}"
                        class="flex items-center gap-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('surat.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Kembali</a>
            </div>
        </div>
    </div>
@endsection