@extends('layouts.app')

@section('title', 'Tugas Admin - Penomoran & Arsip')

@section('content')
    <div x-data="{ openModals: {} }" @open-modal.window="openModals[$event.detail.modal] = true"
        @close-modal.window="openModals[$event.detail.modal] = false">

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Antrian Penomoran & Arsip</h3>
                <p class="text-sm text-gray-600 mt-1">Kelola surat yang sudah ditandatangani Rektor</p>
            </div>
        </div>

        <!-- 1. SURAT PERLU NOMOR (Status: signed_rektor) -->
        <div class="mb-8">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">1</span>
                Menunggu Penomoran
            </h4>

            @if ($signedSurats->isEmpty())
                <div class="bg-white rounded-xl card-shadow p-6 text-center">
                    <p class="text-gray-500">Tidak ada surat yang perlu diberi nomor.</p>
                </div>
            @else
                <div class="bg-white rounded-xl card-shadow overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Perihal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Dari</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">TTD Rektor</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($signedSurats as $surat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($surat->perihal, 40) }}</div>
                                        <div class="text-xs text-gray-500">{{ $surat->jenis_surat_keluar }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $surat->creator->name }}
                                        <div class="text-xs text-gray-400">{{ $surat->creator->unit }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $surat->signed_rektor_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button type="button"
                                            x-on:click="$dispatch('open-modal', { modal: 'numbering-modal-{{ $surat->id }}' })"
                                            class="px-3 py-1.5 bg-purple-600 text-white rounded text-xs hover:bg-purple-700 transition">
                                            Beri Nomor
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- 2. SURAT PERLU ARSIP (Status: numbered) -->
        <div>
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">2</span>
                Siap Diarsipkan
            </h4>
            
            @if ($numberedSurats->isEmpty())
                <div class="bg-white rounded-xl card-shadow p-6 text-center">
                    <p class="text-gray-500">Tidak ada surat yang menunggu arsip.</p>
                </div>
            @else
                <div class="bg-white rounded-xl card-shadow overflow-hidden">
                    <table class="w-full">
                         <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nomor Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Perihal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($numberedSurats as $surat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono text-sm font-bold text-gray-900">
                                        {{ $surat->nomor_surat }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ Str::limit($surat->perihal, 50) }}
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('approval.downloadSigned', $surat->id) }}" target="_blank"
                                            class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded text-xs hover:bg-gray-200 transition">
                                            Download
                                        </a>
                                        <form action="{{ route('approval.archive', $surat->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Arsipkan surat ini sekarang?')"
                                                class="px-3 py-1.5 bg-green-600 text-white rounded text-xs hover:bg-green-700 transition">
                                                Arsipkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- MODALS PENOMORAN -->
        @foreach($signedSurats as $surat)
            <div x-show="openModals['numbering-modal-{{ $surat->id }}']"
                x-cloak
                class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full flex items-center justify-center z-[9999]"
                style="display: none;">
                <div class="relative mx-auto p-6 border shadow-xl rounded-2xl bg-white w-full max-w-md" @click.stop>
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xl font-bold text-gray-900">Input Nomor Surat</h3>
                        <button type="button" x-on:click="$dispatch('close-modal', { modal: 'numbering-modal-{{ $surat->id }}' })"
                            class="text-gray-400 hover:text-gray-600">
                            ✕
                        </button>
                    </div>
                    <form action="{{ route('approval.numbering', $surat->id) }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Nomor Urut</label>
                            <input type="text" name="nomor_urut_manual" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 font-mono"
                                placeholder="Contoh: 001">
                            <p class="mt-2 text-xs text-gray-500">
                                Format: [Nomor]/.../{{ date('m/Y') }}
                            </p>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" x-on:click="$dispatch('close-modal', { modal: 'numbering-modal-{{ $surat->id }}' })"
                                class="px-4 py-2 border rounded-lg">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Simpan Nomor</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
@endsection
