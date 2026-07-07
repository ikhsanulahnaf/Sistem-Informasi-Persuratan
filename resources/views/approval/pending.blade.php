@extends('layouts.app')

@section('title', 'Approval Surat Keluar - Wakil Rektor')

@section('content')
    <div x-data="{ openModals: {} }" @open-modal.window="openModals[$event.detail.modal] = true"
        @close-modal.window="openModals[$event.detail.modal] = false">

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Surat Menunggu Persetujuan</h3>
                <p class="text-sm text-gray-600 mt-1">Persetujuan & Paraf Wakil Rektor</p>
            </div>
        </div>

        @if ($surats->isEmpty())
            <div class="bg-white rounded-xl card-shadow p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-lg">Tidak ada surat yang menunggu persetujuan.</p>
            </div>
        @else
            <div class="bg-white rounded-xl card-shadow overflow-hidden" data-aos="fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Nomor</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Perihal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Dari</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Revisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($surats as $surat)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-mono text-sm font-semibold text-gray-900">
                                        {{ $surat->nomor_surat ?? 'DRAFT' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if($surat->jenis === 'keluar' && $surat->jenis_surat_keluar === 'surat_keluar')
                                            <div class="font-medium">Surat Keluar Umum</div>
                                            <div class="text-xs text-gray-500 mt-1">-</div>
                                        @else
                                            <div class="font-medium">{{ Str::limit($surat->perihal, 40) }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($surat->isi_ringkas, 60) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="font-medium text-gray-900">{{ $surat->creator->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $surat->creator->unit ?? ucfirst($surat->creator->role) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($surat->jenis === 'keluar' && $surat->jenis_surat_keluar === 'surat_keluar')
                                            -
                                        @else
                                            {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d M Y') : '-' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($surat->revision_count > 0)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Revisi #{{ $surat->revision_count }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if ($surat->jenis === 'masuk' || $surat->jenis_surat_keluar === 'surat_keluar')
                                                <a href="{{asset($surat->file_path) }}" target="_blank" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition
                                                                                    inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M4 4a2 2 0 012-2h6a1 1 0 00-.707.293l-5.414 5.414a1 1 0 00-.293.707V12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z">
                                                        </path>
                                                        <path
                                                            d="M12.586 4.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM13.37 5.412l-2.828 2.829m2.828-2.829l1.414 1.414">
                                                        </path>
                                                    </svg>
                                                    Lihat
                                                </a>
                                            @else
                                                <a href="{{ route('surat.show', $surat->id) }}" target="_blank"
                                                    class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M4 4a2 2 0 012-2h6a1 1 0 00-.707.293l-5.414 5.414a1 1 0 00-.293.707V12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z">
                                                        </path>
                                                        <path
                                                            d="M12.586 4.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM13.37 5.412l-2.828 2.829m2.828-2.829l1.414 1.414">
                                                        </path>
                                                    </svg>
                                                    Lihat
                                                </a>
                                            @endif
                                            <form action="{{ route('approval.approve', $surat->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-2 bg-green-600 text-white rounded text-xs hover:bg-green-700 transition inline-flex items-center gap-1"
                                                    onclick="return confirm('Setujui dan paraf surat ini?')">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Setujui
                                                </button>
                                            </form>

                                            <!-- Hanya tombol trigger (modal dipindah ke bawah) -->
                                            <button type="button"
                                                @click="$dispatch('open-modal', { modal: 'modal-tolak-{{ $surat->id }}' })"
                                                class="px-3 py-2 bg-red-600 text-white rounded text-xs hover:bg-red-700 transition inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Tolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- MODAL TOLAK - DI LUAR TABEL --}}
        @foreach ($surats as $surat)
            <div x-show="openModals['modal-tolak-{{ $surat->id }}']" x-cloak
                class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
                @click="openModals['modal-tolak-{{ $surat->id }}'] = false">

                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" @click.stop>
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-red-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 0.5 1a6 6 0 112.8-11.627z" clip-rule="evenodd">
                                </path>
                            </svg>
                            Kembalikan untuk Revisi
                        </h2>
                    </div>

                    <form action="{{ route('approval.reject', $surat->id) }}" method="POST" class="p-6">
                        @csrf
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Surat</p>
                            <p class="font-mono font-semibold text-gray-900 text-sm">{{ $surat->nomor_surat ?? 'DRAFT' }}</p>
                            <p class="text-sm text-gray-700 font-medium mt-2">
                                @if($surat->jenis === 'keluar' && $surat->jenis_surat_keluar === 'surat_keluar')
                                    -
                                @else
                                    {{ $surat->perihal }}
                                @endif
                            </p>
                        </div>

                        <div class="mb-4 pt-4 border-t border-gray-200">
                            <label for="alasan_{{ $surat->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan untuk Revisi <span class="text-red-600">*</span>
                            </label>
                            <textarea id="alasan_{{ $surat->id }}" name="alasan" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm"
                                placeholder="Jelaskan apa yang perlu direvisi..." required></textarea>
                            <p class="text-xs text-gray-500 mt-1">Catatan ini akan dikirim ke departemen penyusunan surat.</p>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="openModals['modal-tolak-{{ $surat->id }}'] = false"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                                Kembalikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection