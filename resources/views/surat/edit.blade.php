@extends('layouts.app')

@section('title', 'Edit Surat Keluar')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl card-shadow p-6" data-aos="zoom-in">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Edit Surat Keluar</h3>
            <p class="text-gray-600 mb-6">Nomor: <span class="font-mono">{{ $surat->nomor_surat ?? 'DRAFT' }}</span></p>

            <form action="{{ route('surat.update', $surat) }}" method="POST" id="suratForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Jenis Surat Keluar (Readonly) -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Jenis Surat Keluar</label>
                    <input type="text"
                        value="{{ $surat->jenis_surat_keluar ? str_replace('_', ' ', ucfirst($surat->jenis_surat_keluar)) : 'Surat Keluar Umum' }}"
                        readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 font-medium">
                    <input type="hidden" name="jenis_surat_keluar" value="{{ $surat->jenis_surat_keluar ?? 'surat_keluar' }}">
                </div>

                @if($surat->jenis_surat_keluar && in_array($surat->jenis_surat_keluar, ['edaran_rektor', 'sk_rektor', 'surat_tugas', 'nota_dinas']))
                    <!-- === FIELD UNTUK SURAT KHUSUS === -->
                    <div class="mb-5">
                        <!-- Perihal / Tentang -->
                        <div class="mb-5">
                            <label class="block text-gray-700 font-medium mb-2">
                                {{ $surat->jenis_surat_keluar === 'sk_rektor' || $surat->jenis_surat_keluar === 'surat_tugas' ? 'Tentang' : 'Perihal / Tentang' }}
                            </label>
                            <input type="text" name="perihal" value="{{ old('perihal', $surat->perihal) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Isian Umum (Edaran, Nota Dinas) -->
                        @if(in_array($surat->jenis_surat_keluar, ['edaran_rektor', 'nota_dinas']))
                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Isi Surat</label>
                                <textarea name="isi_ringkas" rows="6"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Tulis isi surat...">{{ old('isi_ringkas', $surat->isi_ringkas) }}</textarea>
                            </div>
                        @endif

                        <!-- SK REKTOR -->
                        @if($surat->jenis_surat_keluar === 'sk_rektor')
                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Menimbang</label>
                                <div id="menimbangFields">
                                    @foreach($surat->menimbang ?? [''] as $menimbang)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" name="menimbang[]" value="{{ $menimbang }}"
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                            <button type="button" class="px-3 bg-red-500 text-white rounded"
                                                onclick="removeMenimbang(this)">✕</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="text-sm text-blue-600" onclick="addMenimbang()">+ Tambah
                                    Menimbang</button>
                            </div>

                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Menetapkan</label>
                                <div id="menetapkanFields">
                                    @foreach($surat->menetapkan ?? [''] as $menetapkan)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" name="menetapkan[]" value="{{ $menetapkan }}"
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                            <button type="button" class="px-3 bg-red-500 text-white rounded"
                                                onclick="removeMenetapkan(this)">✕</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="text-sm text-blue-600" onclick="addMenetapkan()">+ Tambah
                                    Menetapkan</button>
                            </div>
                        @endif

                        <!-- SURAT TUGAS -->
                        @if($surat->jenis_surat_keluar === 'surat_tugas')
                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Pertimbangan</label>
                                <div id="pertimbanganFields">
                                    @foreach($surat->pertimbangan ?? [''] as $pertimbangan)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" name="pertimbangan[]" value="{{ $pertimbangan }}"
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                            <button type="button" class="px-3 bg-red-500 text-white rounded"
                                                onclick="removePertimbangan(this)">✕</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="text-sm text-blue-600" onclick="addPertimbangan()">+ Tambah
                                    Pertimbangan</button>
                            </div>

                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Dasar</label>
                                <textarea name="dasar" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Contoh: Surat Undangan No. ...">{{ old('dasar', $surat->dasar) }}</textarea>
                            </div>

                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Untuk</label>
                                <div id="untukFields">
                                    @foreach($surat->untuk ?? [''] as $untuk)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" name="untuk[]" value="{{ $untuk }}"
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                            <button type="button" class="px-3 bg-red-500 text-white rounded"
                                                onclick="removeUntuk(this)">✕</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="text-sm text-blue-600" onclick="addUntuk()">+ Tambah Tugas</button>
                            </div>
                        @endif

                        <!-- Tembusan Manual -->
                        @if(in_array($surat->jenis_surat_keluar, ['sk_rektor', 'surat_tugas', 'edaran_rektor', 'nota_dinas']))
                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Tembusan</label>
                                <div id="tembusanFields">
                                    @foreach($surat->tembusan ?? [''] as $tembusan)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" name="tembusan[]" value="{{ $tembusan }}"
                                                class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                            <button type="button" class="px-3 bg-red-500 text-white rounded"
                                                onclick="removeTembusan(this)">✕</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="text-sm text-blue-600" onclick="addTembusan()">+ Tambah
                                    Tembusan</button>
                            </div>

                            <!-- Surat Tembusan (Dropdown Surat Masuk) -->
                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Surat Tembusan</label>
                                <select name="tembusan_surat_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">-- Pilih Surat Masuk --</option>
                                    @foreach($suratMasukList as $suratMasuk)
                                        <option value="{{ $suratMasuk->id }}"
                                            {{ $surat->tembusan_surat_id == $suratMasuk->id ? 'selected' : '' }}>
                                            {{ $suratMasuk->nomor_surat ?? 'DRAFT' }} - {{ \Illuminate\Support\Str::limit($suratMasuk->perihal, 30) }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Pilih surat masuk yang akan dijadikan tembusan.</p>
                            </div>
                        @endif

                        <!-- Tanggal & Pengirim -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat"
                                    value="{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Pengirim</label>
                                <input type="text" name="pengirim" value="{{ $surat->pengirim }}" readonly
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                        </div>

                        <!-- Kepada / Penerima -->
                        <div class="mb-5">
                            <label class="block text-gray-700 font-medium mb-2">
                                {{ $surat->jenis_surat_keluar === 'surat_tugas' || $surat->jenis_surat_keluar === 'nota_dinas' ? 'Kepada' : 'Penerima' }}
                            </label>
                            <input type="text" name="penerima" value="{{ old('penerima', $surat->penerima) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Nama/Nama Instansi Penerima">
                        </div>
                    </div>

                    <!-- Lampiran -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">Ganti Lampiran (Opsional)</label>
                        <input type="file" name="lampiran"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            accept=".pdf,.doc,.docx">
                        @if($surat->lampiran_path)
                            <p class="mt-1 text-sm text-gray-500">
                                Lampiran saat ini: <a href="{{ asset('storage/' . $surat->lampiran_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                            </p>
                        @endif
                        <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengganti lampiran.</p>
                    </div>
                @else
                    <!-- === FIELD UNTUK SURAT KELUAR UMUM === -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Perihal</label>
                            <input type="text" name="perihal" value="{{ old('perihal', $surat->perihal) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat"
                                    value="{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Penerima</label>
                                <input type="text" name="penerima" value="{{ old('penerima', $surat->penerima) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Nama/Nama Instansi Penerima">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Ganti File Surat (Opsional)</label>
                            <input type="file" name="file" accept=".pdf,.doc,.docx"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @if($surat->file_path)
                                <p class="mt-1 text-sm text-gray-500">
                                    File saat ini: <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                                </p>
                            @endif
                            <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengganti file.</p>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('surat.show', $surat) }}"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit"
                        class="btn-primary text-white px-5 py-2 rounded-lg flex items-center gap-2 hover:shadow-lg transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // === FUNGSI DINAMIS ===
        function addMenimbang() {
            const container = document.getElementById('menimbangFields');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `<input type="text" name="menimbang[]" class="flex-1 px-3 py-2 border border-gray-300 rounded"><button type="button" class="px-3 bg-red-500 text-white rounded" onclick="removeMenimbang(this)">✕</button>`;
            container.appendChild(div);
        }
        function removeMenimbang(btn) { btn.parentElement.remove(); }

        function addMenetapkan() {
            const container = document.getElementById('menetapkanFields');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `<input type="text" name="menetapkan[]" class="flex-1 px-3 py-2 border border-gray-300 rounded"><button type="button" class="px-3 bg-red-500 text-white rounded" onclick="removeMenetapkan(this)">✕</button>`;
            container.appendChild(div);
        }
        function removeMenetapkan(btn) { btn.parentElement.remove(); }

        function addPertimbangan() {
            const container = document.getElementById('pertimbanganFields');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `<input type="text" name="pertimbangan[]" class="flex-1 px-3 py-2 border border-gray-300 rounded"><button type="button" class="px-3 bg-red-500 text-white rounded" onclick="removePertimbangan(this)">✕</button>`;
            container.appendChild(div);
        }
        function removePertimbangan(btn) { btn.parentElement.remove(); }

        function addUntuk() {
            const container = document.getElementById('untukFields');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `<input type="text" name="untuk[]" class="flex-1 px-3 py-2 border border-gray-300 rounded"><button type="button" class="px-3 bg-red-500 text-white rounded" onclick="removeUntuk(this)">✕</button>`;
            container.appendChild(div);
        }
        function removeUntuk(btn) { btn.parentElement.remove(); }

        function addTembusan() {
            const container = document.getElementById('tembusanFields');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `<input type="text" name="tembusan[]" class="flex-1 px-3 py-2 border border-gray-300 rounded"><button type="button" class="px-3 bg-red-500 text-white rounded" onclick="removeTembusan(this)">✕</button>`;
            container.appendChild(div);
        }
        function removeTembusan(btn) { btn.parentElement.remove(); }
    </script>
@endsection
