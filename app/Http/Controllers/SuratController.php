<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF;
use setasign\Fpdi\PdfParser\StreamReader;

class SuratController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $query = Surat::query();

    // Filter role departemen - lihat semua suratnya
    if ($user->role === 'departemen') {
        $query->where('created_by', $user->id);
    }
    // Untuk Admin, Rektor, WR - HANYA surat yang BELUM diarsipkan
    elseif (in_array($user->role, ['admin', 'rektor', 'wakil_rektor'])) {
        $query->where('approval_status', '!=', 'archived');
    }

    // Filter berdasarkan parameter jenis
    if ($request->has('jenis') && $request->jenis) {
        $query->where('jenis', $request->jenis);
    }

    // Filter berdasarkan status approval
    if ($request->has('status') && $request->status) {
        $query->where('approval_status', $request->status);
    }

    // Filter berdasarkan tanggal
    if ($request->has('tanggal_dari') && $request->tanggal_dari) {
        $query->where('tanggal_surat', '>=', $request->tanggal_dari);
    }
    if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
        $query->where('tanggal_surat', '<=', $request->tanggal_sampai);
    }

    // Search
    if ($request->has('search') && $request->search) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('nomor_surat', 'like', "%{$searchTerm}%")
              ->orWhere('perihal', 'like', "%{$searchTerm}%")
              ->orWhere('pengirim', 'like', "%{$searchTerm}%")
              ->orWhere('penerima', 'like', "%{$searchTerm}%");
        });
    }

    $surats = $query->with('creator')
                    ->latest()
                    ->paginate(10)
                    ->appends($request->all());

    return view('surat.index', compact('surats'));
}


    public function create()
    {
        // Ambil daftar surat masuk untuk dropdown
        $suratMasukList = Surat::where('jenis', 'masuk')
            // ->whereNotNull('nomor_surat')
            ->latest()
            ->get();

        return view('surat.create', compact('suratMasukList'));
    }



    public function store(Request $request)
    {
        try {
            // 1. Validasi
            $validated = $request->validate([
                'jenis' => 'required|in:masuk,keluar',
            ]);

            // 2. Upload file
            $filePath = null;

if ($request->hasFile('file')) {
    $file = $request->file('file');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('surats'), $filename);

    $filePath = 'surats/' . $filename; // simpan ke DB
}

            // 3. Tembusan
            $tembusan = $request->tembusan ? array_values(array_filter($request->tembusan)) : [];
            $pertimbangan = $request->pertimbangan ? array_filter($request->pertimbangan) : [];
            $untuk = $request->untuk ? array_filter($request->untuk) : [];
            $menimbang = $request->menimbang ? array_filter($request->menimbang) : [];
            $menetapkan = $request->menetapkan ? array_filter($request->menetapkan) : [];


            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $lampiranPath = $request->file('lampiran')->store('lampiran', 'public');
            }
            // 4. Status awal
            // Surat keluar memerlukan approval workflow, surat masuk langsung diarsipkan
            $approvalStatus = $request->jenis === 'keluar' ? 'pending_wr' : 'archived';
            // dd($filePath);
            // 5. Simpan ke database
            if ($request->jenis === 'masuk') {
                $surat = Surat::create([
                    'nomor_surat' => $request->nomor_surat,
                    'tanggal_surat' => $request->tanggal_surat_masuk,
                    'perihal' => $request->perihal_masuk,
                    'isi_ringkas' => $request->isi_ringkas,
                    'pengirim' => $request->pengirim_masuk ?? auth()->user()->name,
                    'penerima' => $request->penerima,
                    'jenis' => $request->jenis,
                    'pertimbangan' => $pertimbangan,
                    'dasar' => $request->dasar,
                    'untuk' => $untuk,
                    'tembusan' => $tembusan,
                    'menimbang' => $menimbang,
                    'menetapkan' => $menetapkan,
                    'jenis_surat_keluar' => $request->jenis_surat_keluar,
                    'file_path' => $filePath,
                    'approval_status' => $approvalStatus,
                    'revision_count' => 0,
                    'created_by' => auth()->id(),
                    'tembusan_surat_id' => $request->tembusan_surat_id,
                ]);

                // Create initial StatusTracking
                \App\Models\StatusTracking::create([
                    'surat_id' => $surat->id,
                    'user_id' => auth()->id(),
                    'status_lama' => 'Baru',
                    'status_baru' => $approvalStatus,
                    'catatan' => 'Surat Masuk baru dibuat dan langsung diarsipkan',
                ]);
            } else {
                // dd($request->tanggal_surat);
                $surat = Surat::create([
                    'nomor_surat' => null,
                    'tanggal_surat' => $request->tanggal_surat,
                    'perihal' => $request->perihal,
                    'isi_ringkas' => $request->isi_ringkas,
                    'pengirim' => $request->pengirim ?? auth()->user()->name,
                    'penerima' => $request->penerima,
                    'jenis' => $request->jenis,
                    'pertimbangan' => $pertimbangan,
                    'dasar' => $request->dasar,
                    'untuk' => $untuk,
                    'tembusan' => $tembusan,
                    'menimbang' => $menimbang,
                    'menetapkan' => $menetapkan,
                    'lampiran_path' => $lampiranPath,
                    'jenis_surat_keluar' => $request->jenis_surat_keluar,
                    'file_path' => $filePath,
                    'approval_status' => $approvalStatus,
                    'revision_count' => 0,
                    'created_by' => auth()->id(),
                    'tembusan_surat_id' => $request->tembusan_surat_id,
                ]);

                // Create initial StatusTracking
                \App\Models\StatusTracking::create([
                    'surat_id' => $surat->id,
                    'user_id' => auth()->id(),
                    'status_lama' => 'Baru',
                    'status_baru' => $approvalStatus,
                    'catatan' => 'Surat Keluar baru diajukan, menunggu approval WR',
                ]);
            }

            // 6. Cek jika gagal create
            if (!$surat) {
                return back()
                    ->withInput()
                    ->with('error', 'Data surat gagal disimpan ke database.');
            }

            return redirect()
                ->route('surat.index')
                ->with('success', 'Surat berhasil diajukan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validasi error (otomatis ditangani Laravel, ini opsional)
            throw $e;

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Gagal menyimpan surat', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan surat. Silakan coba lagi atau hubungi admin.');
        }
    }


    public function show(Surat $surat)
    {
        if (auth()->user()->role === 'departemen' && $surat->created_by !== auth()->id()) {
            abort(403);
        }
        if ($surat->approval_status === 'didisposisi') {
            $disposisi = $surat->disposisi()->latest()->first();
            return view('surat.show', compact('surat', 'disposisi'));
        }
        return view('surat.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        // Permission: Pembuat, WR, dan Rektor bisa edit
        $user = auth()->user();
        $canEdit = $surat->created_by === $user->id
            || in_array($user->role, ['wakil_rektor', 'rektor', 'admin']);

        if (!$canEdit) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit surat ini.');
        }

        if ($surat->jenis !== 'keluar') {
            return redirect()->back()->with('error', 'Hanya surat keluar yang dapat diedit.');
        }

        if (!in_array($surat->approval_status, ['pending_wr', 'rejected_wr', 'waiting_signature'])) {
            return redirect()->back()->with('error', 'Surat tidak dapat diubah pada tahap ini.');
        }

        // Ambil list surat masuk untuk dropdown tembusan
        $suratMasukList = \App\Models\Surat::where('jenis', 'masuk')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('surat.edit', compact('surat', 'suratMasukList'));
    }

    public function update(Request $request, Surat $surat)
    {
        // Permission: Pembuat, WR, dan Rektor bisa update
        $user = auth()->user();
        $canEdit = $surat->created_by === $user->id
            || in_array($user->role, ['wakil_rektor', 'rektor', 'admin']);

        if (!$canEdit) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate surat ini.');
        }

        // Validasi status
        if (!in_array($surat->approval_status, ['pending_wr', 'rejected_wr', 'waiting_signature'])) {
            return redirect()->back()->with('error', 'Surat tidak dapat diubah pada tahap ini.');
        }

        // Validasi dasar
        $request->validate([
            'perihal' => 'required|string',
            'penerima' => 'nullable|string',
            'tanggal_surat' => 'nullable|date',
            'isi_ringkas' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            if ($surat->file_path) {
                Storage::disk('public')->delete($surat->file_path);
            }
            $surat->file_path = $request->file('file')->store('surats', 'public');
        }

        // Handle lampiran
        if ($request->hasFile('lampiran')) {
            if ($surat->lampiran_path) {
                Storage::disk('public')->delete($surat->lampiran_path);
            }
            $surat->lampiran_path = $request->file('lampiran')->store('lampirans', 'public');
        }

        // Data dasar yang selalu diupdate
        $updateData = [
            'perihal' => $request->perihal,
            'penerima' => $request->penerima,
            'tanggal_surat' => $request->tanggal_surat,
        ];

        // Handle field khusus berdasarkan jenis surat keluar
        if ($request->filled('jenis_surat_keluar')) {
            $updateData['jenis_surat_keluar'] = $request->jenis_surat_keluar;

            // SK Rektor
            if ($request->jenis_surat_keluar === 'sk_rektor') {
                $updateData['menimbang'] = $request->menimbang ?? [];
                $updateData['menetapkan'] = $request->menetapkan ?? [];
                $updateData['tembusan'] = $request->tembusan ?? [];
                $updateData['tembusan_surat_id'] = $request->tembusan_surat_id;
            }
            // Surat Tugas
            elseif ($request->jenis_surat_keluar === 'surat_tugas') {
                $updateData['pertimbangan'] = $request->pertimbangan ?? [];
                $updateData['dasar'] = $request->dasar;
                $updateData['untuk'] = $request->untuk ?? [];
                $updateData['tembusan'] = $request->tembusan ?? [];
                $updateData['tembusan_surat_id'] = $request->tembusan_surat_id;
            }
            // Edaran Rektor & Nota Dinas
            elseif (in_array($request->jenis_surat_keluar, ['edaran_rektor', 'nota_dinas'])) {
                $updateData['isi_ringkas'] = $request->isi_ringkas;
                $updateData['tembusan'] = $request->tembusan ?? [];
                $updateData['tembusan_surat_id'] = $request->tembusan_surat_id;
            }
        }

        $surat->update($updateData);

        // Reset approval status jika diubah setelah rejected
        if ($surat->approval_status === 'rejected_wr') {
            $surat->update([
                'approval_status' => 'pending_wr',
                'revision_notes' => null,
            ]);

            \App\Models\StatusTracking::create([
                'surat_id' => $surat->id,
                'user_id' => auth()->id(),
                'status_lama' => 'rejected_wr',
                'status_baru' => 'pending_wr',
                'catatan' => 'Surat direvisi dan diajukan kembali',
            ]);
        }

        return redirect()->route('surat.show', $surat->id)->with('success', 'Surat berhasil diperbarui!');
    }

    public function destroy(Surat $surat)
    {
        if ($surat->created_by !== auth()->id()) {
            abort(403);
        }

        if ($surat->jenis !== 'keluar') {
            return redirect()->back()->with('error', 'Hanya surat keluar yang dapat dihapus.');
        }

        if (!in_array($surat->approval_status, ['pending_wr', 'rejected_wr'])) {
            return redirect()->back()->with('error', 'Hanya surat yang belum disetujui yang dapat dihapus.');
        }

        Storage::disk('public')->delete($surat->file_path);
        $surat->delete();
        return redirect()->route('surat.index')->with('success', 'Surat berhasil dihapus!');
    }

    public function arsip(Request $request)
    {
        $user = auth()->user();

        // Hanya Admin, Rektor, dan WR yang bisa akses arsip
        if (!in_array($user->role, ['admin', 'rektor', 'wakil_rektor'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses arsip.');
        }

        $query = Surat::query()->where('approval_status', 'archived')->orWhere('approval_status', 'didisposisi');

        // Search
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nomor_surat', 'like', "%{$searchTerm}%")
                  ->orWhere('perihal', 'like', "%{$searchTerm}%")
                  ->orWhere('pengirim', 'like', "%{$searchTerm}%")
                  ->orWhere('penerima', 'like', "%{$searchTerm}%");
            });
        }

        // Filter berdasarkan jenis
        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        // Filter berdasarkan jenis surat keluar
        if ($request->has('jenis_surat_keluar') && $request->jenis_surat_keluar) {
            $query->where('jenis_surat_keluar', $request->jenis_surat_keluar);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->where('tanggal_surat', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->where('tanggal_surat', '<=', $request->tanggal_sampai);
        }

        $surats = $query->with('creator')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(20)
                        ->appends($request->all());

        return view('surat.arsip', compact('surats'));
    }

   public function downloadFile(Surat $surat)
{
    // 🔐 Authorization
    if (
        !in_array(auth()->user()->role, ['admin', 'rektor', 'wakil_rektor']) &&
        $surat->created_by !== auth()->id()
    ) {
        abort(403);
    }

    // =====================================================
    // SURAT KHUSUS (Generate dari Template)
    // =====================================================
    if (
        $surat->jenis === 'keluar' &&
        in_array($surat->jenis_surat_keluar, [
            'edaran_rektor',
            'sk_rektor',
            'surat_tugas',
            'nota_dinas'
        ])
    ) {

        // 1️⃣ Generate PDF utama
        $mainPdfContent = Pdf::loadView(
            'surat.templates.' . $surat->jenis_surat_keluar,
            compact('surat')
        )
            ->setPaper('a4', 'portrait')
            ->output();

        $pdfContents = [$mainPdfContent];

        // 2️⃣ Jika ada lampiran PDF → tambahkan ke array
        if (
            $surat->lampiran_path &&
            Storage::disk('public')->exists($surat->lampiran_path) &&
            pathinfo($surat->lampiran_path, PATHINFO_EXTENSION) === 'pdf'
        ) {
            $pdfContents[] = Storage::disk('public')->get($surat->lampiran_path);
        }

        // 3️⃣ Jika lebih dari 1 PDF → merge
        if (count($pdfContents) > 1) {
            $mergedPdf = $this->mergePdf($pdfContents);
            $filename = Str::slug($surat->perihal) . '_dengan_lampiran.pdf';

            return response($mergedPdf)
                ->header('Content-Type', 'application/pdf')
                ->header(
                    'Content-Disposition',
                    'attachment; filename="' . $filename . '"'
                );
        }

        // 4️⃣ Jika hanya 1 PDF
        return response($mainPdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header(
                'Content-Disposition',
                'attachment; filename="' . Str::slug($surat->perihal) . '.pdf"'
            );
    }

    // =====================================================
    // SURAT UMUM / SURAT MASUK (FILE UPLOAD)
    // =====================================================
    if ($surat->file_path && file_exists(public_path($surat->file_path))) {
        return response()->download(public_path($surat->file_path));
    }

    return back()->with('error', 'File tidak ditemukan.');
}

    public function preview(Surat $surat)
    {
        // 🔐 Authorization
        if (
            !in_array(auth()->user()->role, ['admin', 'rektor', 'wakil_rektor']) &&
            $surat->created_by !== auth()->id()
        ) {
            abort(403);
        }

        // =====================================================
        // SURAT KHUSUS (Generate dari Template)
        // =====================================================
        if (
            $surat->jenis === 'keluar' &&
            in_array($surat->jenis_surat_keluar, [
                'edaran_rektor',
                'sk_rektor',
                'surat_tugas',
                'nota_dinas'
            ])
        ) {

            // 1️⃣ Generate PDF utama
            $mainPdfContent = Pdf::loadView(
                'surat.templates.' . $surat->jenis_surat_keluar,
                compact('surat')
            )
                ->setPaper('a4', 'portrait')
                ->output();

            $pdfContents = [$mainPdfContent];

            // 2️⃣ Jika ada lampiran PDF → tambahkan ke array
            if (
                $surat->lampiran_path &&
                Storage::disk('public')->exists($surat->lampiran_path) &&
                pathinfo($surat->lampiran_path, PATHINFO_EXTENSION) === 'pdf'
            ) {
                $pdfContents[] = Storage::disk('public')->get($surat->lampiran_path);
            }

            // 3️⃣ Jika lebih dari 1 PDF → merge
            if (count($pdfContents) > 1) {
                $mergedPdf = $this->mergePdf($pdfContents);
                $filename = Str::slug($surat->perihal) . '_preview.pdf';

                return response($mergedPdf)
                    ->header('Content-Type', 'application/pdf')
                    ->header(
                        'Content-Disposition',
                        'inline; filename="' . $filename . '"'
                    );
            }

            // 4️⃣ Jika hanya 1 PDF
            return response($mainPdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header(
                    'Content-Disposition',
                    'inline; filename="' . Str::slug($surat->perihal) . '.pdf"'
                );
        }

        // =====================================================
        // SURAT UMUM / SURAT MASUK (FILE UPLOAD)
        // =====================================================
        if ($surat->file_path && file_exists(public_path($surat->file_path))) {
             return response()->file(public_path($surat->file_path));
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    // Method bantu untuk menggabungkan PDF

   private function mergePdf(array $pdfContents): string
{
    $pdf = new Fpdi();

    foreach ($pdfContents as $content) {

        // 🔥 INI KUNCI UTAMANYA
        $pageCount = $pdf->setSourceFile(
            StreamReader::createByString($content)
        );

        for ($page = 1; $page <= $pageCount; $page++) {

            $templateId = $pdf->importPage($page);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage(
                $size['orientation'],
                [$size['width'], $size['height']]
            );

            $pdf->useTemplate($templateId);
        }
    }

    return $pdf->Output('', 'S');
}
}