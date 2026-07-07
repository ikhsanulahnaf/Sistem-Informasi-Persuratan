<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Disposisi;
use App\Models\TujuanDisposisi;
use PDF;

class DisposisiController extends Controller
{
    public function create($suratId)
    {
        $surat = Surat::findOrFail($suratId);
        $this->authorizeRektorAndValidSurat($surat);

        // Ambil daftar tujuan disposisi yang aktif, diurutkan berdasarkan urutan
        $tujuanDisposisis = TujuanDisposisi::active()->ordered()->get();

        return view('disposisi.create', compact('surat', 'tujuanDisposisis'));
    }

    public function store(Request $request, $suratId)
    {
        $this->authorizeRektor();
        $surat = Surat::findOrFail($suratId);
        $this->authorizeRektorAndValidSurat($surat);

        $request->validate([
            'instruksi' => 'required|string',
            'tujuan_disposisi_ids' => 'required|array',
            'tujuan_disposisi_ids.*' => 'exists:tujuan_disposisis,id',
            'sifat_disposisi' => 'required|in:rahasia,segera,biasa',
        ]);

        // Buat disposisi
        $disposisi = Disposisi::create([
            'surat_id' => $surat->id,
            'disposer_id' => auth()->id(),
            'instruksi' => $request->instruksi,
            'tujuan_disposisi' => implode(', ', TujuanDisposisi::whereIn('id', $request->tujuan_disposisi_ids)->pluck('nama')->toArray()),
            'sifat_disposisi' => $request->sifat_disposisi,
            'disposed_at' => now(),
        ]);

        // Attach tujuan disposisi (many-to-many)
        $disposisi->tujuans()->attach($request->tujuan_disposisi_ids);

        $surat->update(['approval_status' => 'didisposisi']);

        return redirect()->route('surat.show', $surat->id)->with('success', 'Disposisi berhasil dibuat!');
    }

    public function generatePDF($id)
    {
        $disposisi = Disposisi::with(['surat', 'disposer', 'tujuans'])->findOrFail($id);

        // Format tanggal untuk PDF
        $tanggalDisposisi = $disposisi->disposed_at->locale('id')->translatedFormat('d F Y');
        $bulanRomawi = $this->getRomawiMonth($disposisi->disposed_at->month);
        $tahun = $disposisi->disposed_at->year;

        // Daftar semua tujuan yang tersedia
        $semuaTujuan = [
            'WAREK APK',
            'WAREK BKS',
            'SPMI',
            'PA',
            'PKA',
            'PRPM',
            'PPMB',
            'PDSI',
            'PI2B',
            'BSDMO',
            'BPDK',
            'BPK',
            'BKH',
            'T. ELEKTRO',
            'T. MESIN',
            'T. KIMIA',
            'T. INDUSTRI',
            'T. INFORMATIKA',
            'T. SIPIL',
            'MANAJEMEN',
            'PWK',
            'ARSITEKTUR',
            'TIP',
            'PSPPI',
            'Sekr. Rektor',
        ];

        // Split tujuan menjadi 2 kolom
        $tujuanKiri = array_slice($semuaTujuan, 0, 13);
        $tujuanKanan = array_slice($semuaTujuan, 13);

        // Ambil tujuan yang dipilih
        $tujuanDipilih = $disposisi->tujuans->pluck('nama')->toArray();

        $data = [
            'disposisi' => $disposisi,
            'tanggalDisposisi' => $tanggalDisposisi,
            'bulanRomawi' => $bulanRomawi,
            'tahun' => $tahun,
            'nomorDisposisi' => sprintf('%03d/DISPOSISI/%s/%d', $disposisi->id, $bulanRomawi, $tahun),
            'tujuanKiri' => $tujuanKiri,
            'tujuanKanan' => $tujuanKanan,
            'tujuanDipilih' => $tujuanDipilih,
        ];

        // Override nomor_surat dan pengirim untuk compatibility dengan template
        $disposisi->nomor_surat = $disposisi->surat->nomor_surat;
        $disposisi->pengirim = $disposisi->surat->pengirim;
        $disposisi->tujuan_disposisi = $tujuanDipilih;
        $disposisi->status = $disposisi->sifat_disposisi;

        $pdf = PDF::loadView('disposisi.pdf', $data);

        // Download PDF
        return $pdf->download('disposisi-' . $disposisi->id . '-' . $disposisi->surat->perihal . '.pdf');
    }

    private function getRomawiMonth($month)
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $romawi[$month];
    }

    private function authorizeRektorAndValidSurat(Surat $surat)
    {
        if (auth()->user()->role !== 'rektor') {
            abort(403, 'Hanya Rektor yang dapat membuat disposisi.');
        }

        if ($surat->jenis !== 'masuk') {
            abort(403, 'Hanya surat masuk yang dapat didisposisi.');
        }

        // Surat masuk langsung jadi 'draft' → artinya siap disposisi
        if ($surat->approval_status !== 'archived') {
            abort(403, 'Surat ini sudah diproses sebelumnya.');
        }
    }

    private function authorizeRektor()
    {
        if (auth()->user()->role !== 'rektor') {
            abort(403);
        }
    }
}
