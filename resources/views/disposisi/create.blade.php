@extends('layouts.app')

@section('title', 'Buat Disposisi')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl card-shadow p-6" data-aos="zoom-in">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Disposisi Surat Masuk</h3>
            <p class="text-gray-600 mb-6">Nomor: <span class="font-mono">{{ $surat->nomor_surat }}</span> —
                {{ $surat->perihal }}
            </p>

            <form action="{{ route('disposisi.store', $surat->id) }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Instruksi Disposisi</label>
                        <textarea name="instruksi" required rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Tuliskan instruksi untuk unit terkait..."></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Sifat Disposisi</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sifat_disposisi" value="rahasia"
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-gray-700">Rahasia</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sifat_disposisi" value="segera"
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-gray-700">Segera</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="sifat_disposisi" value="biasa" checked
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-gray-700">Biasa</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">
                            Tujuan Disposisi
                            <span class="text-sm text-gray-500 font-normal">(Tahan Ctrl/Cmd untuk memilih lebih dari satu)</span>
                        </label>
                        <select name="tujuan_disposisi_ids[]" required multiple
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            size="8">
                            @foreach($tujuanDisposisis as $tujuan)
                                <option value="{{ $tujuan->id }}">{{ $tujuan->nama }}</option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-2">
                            💡 Klik pada opsi sambil menahan tombol <strong>Ctrl</strong> (Windows) atau <strong>Cmd</strong> (Mac) untuk memilih lebih dari satu tujuan.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('surat.index') }}"
                            class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit"
                            class="btn-primary text-white px-5 py-2 rounded-lg flex items-center gap-2 hover:shadow-lg transition">
                            Simpan Disposisi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
