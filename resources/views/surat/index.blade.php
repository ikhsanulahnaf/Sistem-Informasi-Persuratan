@extends('layouts.app')

@section('title', 'Kelola Surat')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900">Daftar Surat</h3>
        <a href="{{ route('surat.create') }}"
            class="btn-primary text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:shadow-lg transition"
            data-aos="fade-left">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Surat
        </a>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-xl card-shadow p-5 mb-6" data-aos="fade-down">
        <form method="GET" action="{{ route('surat.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Surat</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nomor/Perihal/Pengirim..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                {{-- Jenis Surat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat</label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                    </select>
                </div>

                {{-- Tanggal Dari --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                {{-- Tanggal Sampai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('surat.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Reset
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl card-shadow overflow-hidden" data-aos="fade-up">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perihal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tembusan
                            Surat</th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan
                            Revisi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($surats as $surat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">
                                {{ $surat->nomor_surat ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($surat->jenis === 'keluar' && $surat->jenis_surat_keluar === 'surat_keluar')
                                    -
                                @else
                                    {{ Str::limit($surat->perihal, 40) }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                                                                                                                                                            @if($surat->jenis === 'masuk') bg-blue-100 text-blue-800
                                                                                                                                                                                            @else bg-purple-100 text-purple-800
                                                                                                                                                                                            @endif">
                                    {{ ucfirst($surat->jenis) }}
                                </span>
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
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                            </td>
                            <td>
                                <span class="text-sm text-gray-600">
                                    {{ $surat->revision_notes ? Str::limit($surat->revision_notes, 50) : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('surat.show', $surat) }}" class="text-blue-600 hover:text-blue-900"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    @if(in_array($surat->approval_status, ['pending_wr']) && $surat->created_by == auth()->id())
                                        <a href="{{ route('surat.edit', $surat) }}" class="text-yellow-600 hover:text-yellow-900"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if(in_array($surat->approval_status, ['pending_wr', 'rejected_wr']) && $surat->created_by == auth()->id())
                                        <form action="{{ route('surat.destroy', $surat) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Yakin hapus surat ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tombol Disposisi (hanya Rektor, untuk surat masuk) -->
                                    @if(auth()->user()->role === 'rektor' && $surat->jenis === 'masuk' && $surat->approval_status === 'draft')
                                        <a href="{{ route('disposisi.create', $surat->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900" title="Buat Disposisi">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </a>
                                    @endif

                                    <!-- Download File -->
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Belum ada surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $surats->links() }}
        </div>
    </div>
@endsection