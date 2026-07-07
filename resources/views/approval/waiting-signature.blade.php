@extends('layouts.app')

@section('title', 'Penandatanganan Surat - Rektor')

@section('content')
    <div x-data="{ openModals: {} }" @open-modal.window="openModals[$event.detail.modal] = true"
        @close-modal.window="openModals[$event.detail.modal] = false">

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Surat Menunggu Penandatanganan</h3>
                <p class="text-sm text-gray-600 mt-1">Penandatanganan & Penomoran oleh Rektor</p>
            </div>
        </div>

        @if ($surats->isEmpty())
            <div class="bg-white rounded-xl card-shadow p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-lg">Tidak ada surat yang menunggu penandatanganan.</p>
            </div>
        @else
            <div class="bg-white rounded-xl card-shadow overflow-hidden" data-aos="fade-up">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-purple-50 to-purple-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Nomor</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Perihal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Dari</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Diparaf WR</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Tembusan Surat
                                </th>
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
                                    <td class="px-6 py-4 text-sm">
                                        @if ($surat->parafBy)
                                            <div class="font-medium text-green-700">✓ {{ $surat->parafBy->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $surat->paraf_wr_at->format('d M Y H:i') }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($surat->jenis === 'keluar' && $surat->jenis_surat_keluar === 'surat_keluar')
                                            -
                                        @else
                                            {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d M Y') : '-' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($surat->tembusan_surat_id)
                                            <a href="{{ route('surat.show', $surat->tembusanSurat) }}"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                {{ $surat->tembusanSurat->nomor_surat ?? 'DRAFT' }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            {{-- Preview untuk semua surat --}}
                                            @if($surat->jenis_surat_keluar === 'surat_keluar')
                                                <a href="{{asset($surat->file_path)}}" target="_blank"
                                                    class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M4 4a2 2 0 012-2h6a1 1 0 00-.707.293l-5.414 5.414a1 1 0 00-.293.707V12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z">
                                                        </path>
                                                        <path
                                                            d="M12.586 4.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM13.37 5.412l-2.828 2.829m2.828-2.829l1.414 1.414">
                                                        </path>
                                                    </svg> Lihat
                                                </a>
                                            @else
                                                {{-- Preview PDF untuk Rektor --}}
                                                <a href="{{ route('approval.previewForRektor', $surat->id) }}" target="_blank"
                                                    class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M4 4a2 2 0 012-2h6a1 1 0 00-.707.293l-5.414 5.414a1 1 0 00-.293.707V12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z">
                                                        </path>
                                                        <path
                                                            d="M12.586 4.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM13.37 5.412l-2.828 2.829m2.828-2.829l1.414 1.414">
                                                        </path>
                                                    </svg> Preview
                                                </a>
                                            @endif

                                            {{-- Status: Menunggu Approval Rektor (approved_wr / waiting_rektor_approval) --}}
                                            @if(in_array($surat->approval_status, ['approved_wr', 'waiting_rektor_approval']))
                                                {{-- Tombol Approve --}}
                                                <form action="{{ route('approval.approveRektor', $surat->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-3 py-2 bg-green-600 text-white rounded text-xs hover:bg-green-700 transition inline-flex items-center gap-1"
                                                        onclick="return confirm('Setujui surat ini untuk ditandatangani?')">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Setujui
                                                    </button>
                                                </form>

                                                {{-- Tombol Revisi --}}
                                                <button type="button"
                                                    x-on:click="$dispatch('open-modal', { modal: 'reject-modal-{{ $surat->id }}' })"
                                                    class="px-3 py-2 bg-red-600 text-white rounded text-xs hover:bg-red-700 transition inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Revisi
                                                </button>
                                            @endif

                                            {{-- Status: Sudah Diapprove Rektor (approved_rektor) --}}
                                                {{-- Tombol TTD Digital (Langsung Sign, Nomor nanti oleh Admin) --}}
                                                <form action="{{ route('approval.sign', $surat->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-3 py-2 bg-purple-600 text-white rounded text-xs hover:bg-purple-700 transition inline-flex items-center gap-1"
                                                        onclick="return confirm('Tandatangani surat ini secara digital?')">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                            </path>
                                                        </svg>
                                                        TTD Digital
                                                    </button>
                                                </form>

                                                {{-- Bisa tetap minta revisi --}}
                                                <button type="button"
                                                    x-on:click="$dispatch('open-modal', { modal: 'reject-modal-{{ $surat->id }}' })"
                                                    class="px-3 py-2 bg-orange-600 text-white rounded text-xs hover:bg-orange-700 transition inline-flex items-center gap-1"
                                                    title="Minta Revisi">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            

                                            {{-- Status: Ditolak Rektor (rejected_rektor) --}}
                                            @if($surat->approval_status === 'rejected_rektor')
                                                <span class="px-3 py-2 bg-red-100 text-red-800 rounded text-xs inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Perlu Revisi
                                                </span>
                                                @if($surat->revision_notes)
                                                    <div class="text-xs text-red-600 max-w-xs" title="{{ $surat->revision_notes }}">
                                                        Catatan: {{ Str::limit($surat->revision_notes, 30) }}
                                                    </div>
                                                @endif
                                            @endif

                                            {{-- Status: Sudah Bernomor (numbered) --}}
                                            @if($surat->approval_status === 'numbered')
                                                <a href="{{ route('approval.downloadSigned', $surat->id) }}"
                                                    class="px-3 py-2 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700 transition inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Download
                                                </a>

                                                <a href="{{ route('approval.verifySignature', $surat->id) }}"
                                                    class="px-3 py-2 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    Verify
                                                </a>


                                            @endif

                                            {{-- Status: Archived/Returned --}}
                                            @if(in_array($surat->approval_status, ['archived', 'returned']))
                                                <a href="{{ route('approval.downloadSigned', $surat->id) }}"
                                                    class="px-3 py-2 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700 transition inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Download
                                                </a>

                                                <a href="{{ route('approval.verifySignature', $surat->id) }}"
                                                    class="px-3 py-2 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    Verify
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Modal Revisi - Ditaruh di luar tabel agar positioning bekerja dengan benar --}}
        @foreach($surats as $surat)


            @if(in_array($surat->approval_status, ['approved_wr', 'waiting_rektor_approval', 'approved_rektor']))
                <div x-show="openModals['reject-modal-{{ $surat->id }}']"
                    x-cloak
                    class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full flex items-center justify-center z-[9999]"
                    style="display: none;"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <div class="relative mx-auto p-6 border shadow-xl rounded-2xl bg-white w-full max-w-md"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        @click.stop>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Minta Revisi Surat
                            </h3>
                            <button type="button"
                                x-on:click="$dispatch('close-modal', { modal: 'reject-modal-{{ $surat->id }}' })"
                                class="text-gray-400 hover:text-gray-600 transition-colors rounded-full p-1 hover:bg-gray-100">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <form action="{{ route('approval.rejectRektor', $surat->id) }}" method="POST">
                            @csrf
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Catatan Revisi</label>
                                <textarea name="revision_notes" rows="5" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all resize-none"
                                    placeholder="Jelaskan perbaikan yang diperlukan...">{{ old('revision_notes') }}</textarea>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button"
                                    x-on:click="$dispatch('close-modal', { modal: 'reject-modal-{{ $surat->id }}' })"
                                    class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors shadow-sm hover:shadow-md">
                                    Kirim Revisi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection