@extends('layouts.app')

@section('title', 'Ajukan Surat Baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl card-shadow p-6" data-aos="zoom-in">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Ajukan Surat Baru</h3>

            <form action="{{ route('surat.store') }}" method="POST" id="suratForm" enctype="multipart/form-data">
                @csrf

                <!-- Jenis Surat -->
                <div class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Jenis Surat</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="jenis" value="masuk" class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2">Surat Masuk</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="jenis" value="keluar" class="text-blue-600 focus:ring-blue-500"
                                checked>
                            <span class="ml-2">Surat Keluar</span>
                        </label>
                    </div>
                </div>

                <!-- Jenis Surat Keluar Khusus -->
                <div id="jenisKeluarContainer" class="mb-5 hidden">
                    <label class="block text-gray-700 font-medium mb-2">Jenis Surat Keluar</label>
                    <select name="jenis_surat_keluar"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="edaran_rektor">Edaran Rektor</option>
                        <option value="sk_rektor">SK Rektor</option>
                        <option value="surat_tugas">Surat Tugas</option>
                        <option value="nota_dinas">Nota Dinas</option>
                        <option value="surat_keluar">Surat Keluar Umum</option>
                    </select>
                </div>

                <!-- === FIELD UNTUK SURAT KHUSUS === -->
                <div id="fieldKhususContainer" class="mb-5 hidden">
                    <!-- Perihal / Tentang -->
                    <div id="perihalContainer" class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">Perihal / Tentang</label>
                        <input type="text" name="perihal"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Isi perihal surat">
                    </div>

                    <!-- Isian Umum (Edaran, Nota Dinas) -->
                    <div id="isiRingkasContainer" class="mb-5 hidden">
                        <label class="block text-gray-700 font-medium mb-2">Isi Surat</label>
                        <textarea name="isi_ringkas" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Tulis isi surat..."></textarea>
                    </div>

                    <!-- SK REKTOR -->
                    <div id="menimbangContainer" class="mb-5 hidden">
                        <label class="block text-gray-700 font-medium mb-2">Menimbang</label>
                        <div id="menimbangFields">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="menimbang[]"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                <button type="button" class="px-3 bg-red-500 text-white rounded"
                                    onclick="removeMenimbang(this)">✕</button>
                            </div>
                        </div>
                        <button type="button" class="text-sm text-blue-600" onclick="addMenimbang()">+ Tambah
                            Menimbang</button>
                    </div>

                    <div id="menetapkanContainer" class="mb-5 hidden">
                        <label class="block text-gray-700 font-medium mb-2">Menetapkan</label>
                        <div id="menetapkanFields">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="menetapkan[]"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                <button type="button" class="px-3 bg-red-500 text-white rounded"
                                    onclick="removeMenetapkan(this)">✕</button>
                            </div>
                        </div>
                        <button type="button" class="text-sm text-blue-600" onclick="addMenetapkan()">+ Tambah
                            Menetapkan</button>
                    </div>

                    <!-- SURAT TUGAS -->
                    <div id="pertimbanganContainer" class="mb-5 hidden">
                        <label class="block text-gray-700 font-medium mb-2">Pertimbangan</label>
                        <div id="pertimbanganFields">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="pertimbangan[]"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                <button type="button" class="px-3 bg-red-500 text-white rounded"
                                    onclick="removePertimbangan(this)">✕</button>
                            </div>
                        </div>
                        <button type="button" class="text-sm text-blue-600" onclick="addPertimbangan()">+ Tambah
                            Pertimbangan</button>
                    </div>

                    <div id="dasarContainer" class="mb-5 hidden">
                        <label class="block text-gray-700 font-medium mb-2">Dasar</label>
                        <textarea name="dasar" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: Surat Undangan No. ..."></textarea>
                    </div>

                    <div id="untukContainer" class="mb-5 hidden">
                        <label class="block text-gray-700 font-medium mb-2">Untuk</label>
                        <div id="untukFields">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="untuk[]" class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                <button type="button" class="px-3 bg-red-500 text-white rounded"
                                    onclick="removeUntuk(this)">✕</button>
                            </div>
                        </div>
                        <button type="button" class="text-sm text-blue-600" onclick="addUntuk()">+ Tambah Tugas</button>
                    </div>

                    <!-- Tembusan Manual -->
                    <div id="tembusanContainer" class="mb-5 hidden">
                        <label class="block text-gray-700 font-medium mb-2">Tembusan</label>
                        <div id="tembusanFields">
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="tembusan[]"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded">
                                <button type="button" class="px-3 bg-red-500 text-white rounded"
                                    onclick="removeTembusan(this)">✕</button>
                            </div>
                        </div>
                        <button type="button" class="text-sm text-blue-600" onclick="addTembusan()">+ Tambah
                            Tembusan</button>
                    </div>

                    <!-- Surat Tembusan (Dropdown Surat Masuk) -->
                    <div id="suratTembusanContainer" class="mb-5 hidden">
                        <label class="block text-gray-700 font-medium mb-2">Surat Tembusan</label>
                        <select name="tembusan_surat_id" id="tembusanSelect" style="width: 100%;"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Cari Surat Masuk (Ketik Nomor/Perihal) --</option>
                            @foreach($suratMasukList as $suratMasuk)
                                <option value="{{ $suratMasuk->id }}">
                                    {{ $suratMasuk->nomor_surat ?? 'DRAFT' }} - {{ Str::limit($suratMasuk->perihal, 50) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Ketik untuk mencari nomor surat atau perihal.</p>

                        <!-- Load CSS Select2 -->
                        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                        <style>
                            /* Custom Style untuk Select2 agar match dengan Tailwind */
                            .select2-container .select2-selection--single {
                                height: 42px;
                                border: 1px solid #d1d5db; /* gray-300 */
                                border-radius: 0.5rem; /* rounded-lg */
                                display: flex;
                                align-items: center;
                            }
                            .select2-container--default .select2-selection--single .select2-selection__arrow {
                                height: 40px;
                            }
                            .select2-container--default .select2-selection--single .select2-selection__rendered {
                                padding-left: 1rem;
                                color: #374151; /* gray-700 */
                            }
                        </style>
                    </div>

                    <!-- Tanggal & Pengirim -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Pengirim</label>
                            <input type="text" name="pengirim" value="{{ auth()->user()->name }}" readonly
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>
                    </div>

                    <!-- Kepada / Penerima -->
                    <div id="kepadaContainer" class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">Penerima</label>
                        <input type="text" name="penerima"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Nama/Nama Instansi Penerima">
                    </div>
                </div>

                <!-- === FIELD UNTUK SURAT MASUK === -->
                <div id="fieldMasukContainer" class="mb-5 hidden">
                    <!-- Nomor Surat -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">Nomor Surat</label>
                        <input type="text" name="nomor_surat"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Contoh: 123/ITI/2026">
                    </div>

                    <!-- Dari -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">Dari</label>
                        <input type="text" name="pengirim_masuk"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Nama/Nama Instansi Pengirim">
                    </div>

                    <!-- Perihal -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">Perihal</label>
                        <input type="text" name="perihal_masuk"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Isi perihal surat">
                    </div>

                    <!-- Tanggal Surat -->
                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat_masuk"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Upload File Surat Resmi (HANYA untuk Surat Masuk & Surat Keluar Umum) -->
                <div id="fileSuratContainer" class="mb-5">
                    <label class="block text-gray-700 font-medium mb-2">Upload File Surat Resmi (PDF/DOC)</label>
                    <input type="file" name="file"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        accept=".pdf,.doc,.docx">
                    <p class="mt-1 text-sm text-gray-500">Unggah file surat resmi dari luar kampus.</p>
                </div>

                <!-- Lampiran (HANYA untuk Surat Khusus) -->
                <div id="lampiranContainer" class="mb-5 hidden">
                    <label class="block text-gray-700 font-medium mb-2">Lampiran (Opsional)</label>
                    <input type="file" name="lampiran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        accept=".pdf,.doc,.docx">
                    <p class="mt-1 text-sm text-gray-500">Unggah lampiran tambahan (jika ada).</p>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('surat.index') }}"
                        class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit"
                        class="btn-primary text-white px-5 py-2 rounded-lg flex items-center gap-2 hover:shadow-lg transition">
                        Ajukan Surat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Load jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 pada elemen dengan ID tembusanSelect
            $('#tembusanSelect').select2({
                placeholder: "-- Cari Surat Masuk --",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "Surat tidak ditemukan";
                    }
                }
            });
        });

        function toggleFormFields() {
            const jenis = document.querySelector('input[name="jenis"]:checked').value;

            const jenisKeluar = document.getElementById('jenisKeluarContainer');
            const fieldKhusus = document.getElementById('fieldKhususContainer');
            const fieldMasuk = document.getElementById('fieldMasukContainer');
            const fileSurat = document.getElementById('fileSuratContainer');
            const lampiran = document.getElementById('lampiranContainer');

            if (jenis === 'keluar') {
                jenisKeluar.classList.remove('hidden');
                fieldMasuk.classList.add('hidden');
            } else {
                jenisKeluar.classList.add('hidden');
                fieldMasuk.classList.remove('hidden');
            }

            // Default: tampilkan upload file
            fileSurat.classList.remove('hidden');
            fieldKhusus.classList.add('hidden');
            lampiran.classList.add('hidden');
        }

        function handleJenisKeluarChange() {
            const selected = document.querySelector('select[name="jenis_surat_keluar"]').value;
            const fieldKhusus = document.getElementById('fieldKhususContainer');
            const fileSurat = document.getElementById('fileSuratContainer');
            const lampiran = document.getElementById('lampiranContainer');
            const kepadaLabel = document.querySelector('#kepadaContainer label');

            const isKhusus = ['edaran_rektor', 'sk_rektor', 'surat_tugas', 'nota_dinas'].includes(selected);

            if (isKhusus) {
                // Sembunyikan upload file surat resmi
                fileSurat.classList.add('hidden');
                // Tampilkan field khusus + lampiran opsional
                fieldKhusus.classList.remove('hidden');
                lampiran.classList.remove('hidden');

                // Sesuaikan label "Penerima" → "Kepada" jika perlu
                if (selected === 'surat_tugas' || selected === 'nota_dinas') {
                    kepadaLabel.textContent = 'Kepada';
                } else {
                    kepadaLabel.textContent = 'Penerima';
                }

                // Tampilkan field sesuai jenis
                hideAllSpecialFields();
                if (selected === 'sk_rektor') {
                    document.getElementById('menimbangContainer').classList.remove('hidden');
                    document.getElementById('menetapkanContainer').classList.remove('hidden');
                    document.getElementById('tembusanContainer').classList.remove('hidden');
                    document.getElementById('suratTembusanContainer').classList.remove('hidden');
                    document.querySelector('#perihalContainer label').textContent = 'Tentang';
                } else if (selected === 'surat_tugas') {
                    document.getElementById('pertimbanganContainer').classList.remove('hidden');
                    document.getElementById('dasarContainer').classList.remove('hidden');
                    document.getElementById('untukContainer').classList.remove('hidden');
                    document.getElementById('suratTembusanContainer').classList.remove('hidden');
                    document.getElementById('tembusanContainer').classList.remove('hidden');
                    document.querySelector('#perihalContainer label').textContent = 'Tentang';
                } else if (selected === 'edaran_rektor' || selected === 'nota_dinas') {
                    document.getElementById('isiRingkasContainer').classList.remove('hidden');
                    document.getElementById('tembusanContainer').classList.remove('hidden');
                    document.getElementById('suratTembusanContainer').classList.remove('hidden');
                    document.querySelector('#perihalContainer label').textContent = 'Perihal / Tentang';
                }
            } else {
                // Surat Keluar Umum → tampilkan upload file, sembunyikan field khusus
                fileSurat.classList.remove('hidden');
                fieldKhusus.classList.add('hidden');
                lampiran.classList.add('hidden');
            }
        }

        function hideAllSpecialFields() {
            document.getElementById('isiRingkasContainer').classList.add('hidden');
            document.getElementById('menimbangContainer').classList.add('hidden');
            document.getElementById('menetapkanContainer').classList.add('hidden');
            document.getElementById('pertimbanganContainer').classList.add('hidden');
            document.getElementById('dasarContainer').classList.add('hidden');
            document.getElementById('untukContainer').classList.add('hidden');
            document.getElementById('tembusanContainer').classList.add('hidden');
            document.getElementById('suratTembusanContainer').classList.add('hidden');
        }

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

        // Event listeners
        document.querySelectorAll('input[name="jenis"]').forEach(radio => {
            radio.addEventListener('change', toggleFormFields);
        });
        document.querySelector('select[name="jenis_surat_keluar"]').addEventListener('change', handleJenisKeluarChange);

        // Init
        toggleFormFields();
    </script>
@endsection