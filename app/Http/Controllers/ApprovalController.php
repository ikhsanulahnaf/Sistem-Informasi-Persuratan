<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Approval;
use App\Models\EcdsaKeyPair;
use App\Models\DigitalSignature;
use App\Services\EcdsaSigningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF;
class ApprovalController extends Controller
{
    protected $ecdsaService;

    public function __construct(EcdsaSigningService $ecdsaService)
    {
        $this->ecdsaService = $ecdsaService;
    }

    /**
     * Tampilkan surat yang menunggu approval WR
     */
    public function pending()
    {
        // Hanya Wakil Rektor yang bisa melihat
        if (Auth::user()->role !== 'wakil_rektor' && Auth::user()->role !== 'rektor') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $surats = Surat::where('approval_status', 'pending_wr')
            ->with('creator')
            ->latest()
            ->get();

        return view('approval.pending', compact('surats'));
    }

    /**
     * Setujui surat dengan paraf
     */
    public function approve(Surat $surat)
    {
        if (Auth::user()->role !== 'wakil_rektor') {
            return redirect()->back()->with('error', 'Hanya Wakil Rektor yang dapat menyetujui surat.');
        }

        // Harus dalam status pending_wr
        if ($surat->approval_status !== 'pending_wr') {
            return redirect()->back()->with('error', 'Surat tidak dalam status menunggu persetujuan.');
        }

        // Pastikan surat keluar
        if ($surat->jenis !== 'keluar') {
            return redirect()->back()->with('error', 'Hanya surat keluar yang memerlukan persetujuan.');
        }

        // Update status menjadi approved dan catat paraf
        $surat->update([
            'approval_status' => 'approved_wr',
            'paraf_wr_by' => Auth::id(),
            'paraf_wr_at' => now(),
        ]);

        // Buat record approval untuk dokumentasi
        Approval::create([
            'surat_id' => $surat->id,
            'approver_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        \App\Models\StatusTracking::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'status_lama' => 'pending_wr',
            'status_baru' => 'approved_wr',
            'catatan' => 'Surat disetujui oleh Wakil Rektor',
        ]);

        return redirect()->back()->with('success', 'Surat telah disetujui dan diparaf.');
    }

    /**
     * Tolak surat dan kembalikan ke departemen untuk revisi
     */
    public function reject(Request $request, Surat $surat)
    {
        if (Auth::user()->role !== 'wakil_rektor') {
            return redirect()->back()->with('error', 'Hanya Wakil Rektor yang dapat menolak surat.');
        }

        if ($surat->approval_status !== 'pending_wr') {
            return redirect()->back()->with('error', 'Surat tidak dalam status menunggu persetujuan.');
        }

        if ($surat->jenis !== 'keluar') {
            return redirect()->back()->with('error', 'Operasi tidak valid untuk surat masuk.');
        }

        $validated = $request->validate([
            'alasan' => 'required|string|min:10',
        ]);

        // Update status menjadi rejected dan catat catatan revisi
        $surat->update([
            'approval_status' => 'rejected_wr',
            'revision_notes' => $validated['alasan'],
            'revision_count' => $surat->revision_count + 1,
        ]);

        // Buat record approval untuk dokumentasi
        Approval::create([
            'surat_id' => $surat->id,
            'approver_id' => Auth::id(),
            'catatan' => $validated['alasan'],
            'approved_at' => now(),
        ]);

        \App\Models\StatusTracking::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'status_lama' => 'pending_wr',
            'status_baru' => 'rejected_wr',
            'catatan' => 'Surat ditolak Wakil Rektor. Alasan: ' . $validated['alasan'],
        ]);

        return redirect()->back()->with('success', 'Surat telah ditolak dan dikembalikan untuk revisi.');
    }


    private function mergePdf(array $pdfContents): string
    {
        // Buat instance TCPDF melalui FPDI
        $pdf = new Fpdi();

        foreach ($pdfContents as $content) {
            // Tambahkan konten PDF ke FPDI
            $pageCount = $pdf->setSourceFile('@' . $content);

            for ($i = 1; $i <= $pageCount; $i++) {
                $importedPage = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($importedPage);

                // Tambahkan halaman baru di TCPDF
                $pdf->AddPage(
                    $size['orientation'] === 'L' ? 'L' : 'P',
                    [$size['width'], $size['height']]
                );

                // Tempel konten halaman
                $pdf->useTemplate($importedPage);
            }
        }

        // Output sebagai string
        return $pdf->Output('', 'S');
    }
    /**
     * Lihat approval yang sudah disetujui (untuk Rektor)
     */
    public function waitingSignature()
    {
        // Hanya Rektor yang bisa melihat
        if (Auth::user()->role !== 'rektor') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        // Tampilkan surat yang perlu approval/TTD:
        // - approved_wr (perlu approval rektor)
        // - waiting_rektor_approval (sudah dipreview, menunggu approve)
        // - approved_rektor (sudah diapprove, siap TTD)
        // - rejected_rektor (perlu direset dan diapprove ulang)
        $surats = Surat::where(function ($query) {
            $query->where('approval_status', 'approved_wr')
                ->orWhere('approval_status', 'waiting_rektor_approval')
                ->orWhere('approval_status', 'approved_rektor')
                ->orWhere('approval_status', 'numbered')
                ->orWhere('approval_status', 'rejected_rektor');
        })
            ->where('jenis', 'keluar') // Hanya surat keluar yang perlu TTD
            ->with(['creator', 'parafBy'])
            ->latest()
            ->get();

        return view('approval.waiting-signature', compact('surats'));
    }

    /**
     * Tanda tangani surat (Rektor) dengan ECDSA Digital Signature
     */
    public function sign(Surat $surat)
    {
        if (Auth::user()->role !== 'rektor') {
            return redirect()->back()->with('error', 'Hanya Rektor yang dapat menandatangani surat.');
        }

        // Double approval: harus approved_rektor dulu baru bisa TTD
        if ($surat->approval_status !== 'approved_rektor') {
            return redirect()->back()->with('error', 'Surat harus disetujui terlebih dahulu sebelum ditandatangani.');
        }

        if ($surat->jenis !== 'keluar') {
            return redirect()->back()->with('error', 'Hanya surat keluar yang memerlukan tanda tangan digital.');
        }

        try {
            // Get atau generate ECDSA key pair untuk Rektor
            $keyPair = EcdsaKeyPair::where('user_id', Auth::id())->first();

            if (!$keyPair) {
                $keys = $this->ecdsaService->generateKeyPair();
                $keyPair = EcdsaKeyPair::create([
                    'user_id' => Auth::id(),
                    'public_key' => $keys['public_key'],
                    'private_key' => $keys['private_key'],
                    'algorithm' => 'ECDSA',
                    'curve' => 'prime256v1',
                    'generated_at' => now(),
                ]);
            }

            // === TENTUKAN KONTEN FILE YANG AKAN DITANDATANGANI ===
            if ($surat->jenis_surat_keluar === 'surat_keluar') {
                // Gunakan file yang diupload
                $filePath = public_path($surat->file_path);
                if (!file_exists($filePath)) {
                    return redirect()->back()->with('error', 'File surat tidak ditemukan.');
                }
                $fileContent = file_get_contents($filePath);
            } else {
                // Generate PDF dari template
                $pdf = Pdf::loadView('surat.templates.' . $surat->jenis_surat_keluar, compact('surat'))
                    ->setPaper('a4', 'portrait');
                $mainPdfContent = $pdf->output(); // PDF utama sebagai string

                // Cek apakah ada lampiran
                if ($surat->lampiran_path && Storage::exists('public/' . $surat->lampiran_path)) {
                    // Gabungkan PDF utama + lampiran
                    $lampiranPath = storage_path('app/public/' . $surat->lampiran_path);

                    // Pastikan lampiran adalah PDF
                    if (pathinfo($lampiranPath, PATHINFO_EXTENSION) !== 'pdf') {
                        $fileContent = $mainPdfContent;
                    } else {
                        $fileContent = $this->mergePdf([$mainPdfContent, file_get_contents($lampiranPath)]);
                    }
                } else {
                    $fileContent = $mainPdfContent;
                }
            }

            // Sign dengan private key
            $signature = $this->ecdsaService->sign($fileContent, $keyPair->private_key);

            // Verify signature untuk memastikan valid
            $isValid = $this->ecdsaService->verify($fileContent, $signature, $keyPair->public_key);

            if (!$isValid) {
                return redirect()->back()->with('error', 'Gagal membuat signature digital.');
            }

            // Simpan file signed
            $signedFileName = 'signed_' . ($surat->nomor_surat ?? 'draft') . '_' . now()->format('YmdHis') . '.pdf';
            $signedFilePath = 'signed-documents/' . $signedFileName;
            Storage::put($signedFilePath, $fileContent);

            // Simpan digital signature ke database
            DigitalSignature::create([
                'surat_id' => $surat->id,
                'signer_id' => Auth::id(),
                'algorithm' => 'ECDSA',
                'public_key' => $keyPair->public_key,
                'signature_data' => $signature,
                'signed_file_path' => $signedFilePath,
                'signed_at' => now(),
            ]);

            // Update surat status -> signed_rektor (Menunggu Nomor Admin)
            $surat->update([
                'approval_status' => 'signed_rektor',
                'signed_rektor_by' => Auth::id(),
                'signed_rektor_at' => now(),
            ]);

            \App\Models\StatusTracking::create([
                'surat_id' => $surat->id,
                'user_id' => Auth::id(),
                'status_lama' => 'approved_rektor',
                'status_baru' => 'signed_rektor',
                'catatan' => 'Surat ditandatangani oleh Rektor',
            ]);

            return redirect()->back()->with('success', 'Surat telah ditandatangani. Menunggu pemberian nomor oleh Admin.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat digital signature: ' . $e->getMessage());
        }
    }

    /**
     * Beri Nomor Surat (Admin)
     */
    public function numbering(Request $request, Surat $surat)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya Admin yang dapat memberi nomor surat.');
        }

        if ($surat->approval_status !== 'signed_rektor') {
            return redirect()->back()->with('error', 'Surat harus sudah ditandatangani Rektor.');
        }

        $validated = $request->validate([
            'nomor_urut_manual' => 'required|string|max:10',
        ]);

        // === PENOMORAN (MANUAL) ===
        // Tentukan tanggal penomoran
        if ($surat->jenis_surat_keluar === 'surat_keluar') {
            $tanggalPenomoran = now();
        } else {
            $tanggalPenomoran = $surat->tanggal_surat;
        }

        $bulan = $tanggalPenomoran->format('n'); // 1-12
        $tahun = $tanggalPenomoran->format('Y');

        // Gunakan nomor manual dari input
        $nomor_urut = $validated['nomor_urut_manual'];

        // Format nomor sesuai jenis
        $nomor_lengkap = match ($surat->jenis_surat_keluar) {
            'sk_rektor' => "{$nomor_urut}/Kept-lTI/{$bulan}/{$tahun}",
            'edaran_rektor' => "{$nomor_urut}/E/R-ITI/{$bulan}/{$tahun}",
            'surat_tugas' => "{$nomor_urut}/R-ITI/{$bulan}/{$tahun}",
            'nota_dinas' => "{$nomor_urut}/INT/R-ITI/{$bulan}/{$tahun}",
            default => "{$nomor_urut}/KL/{$bulan}/{$tahun}",
        };

        $surat->update([
            'approval_status' => 'numbered',
            'nomor_surat' => $nomor_lengkap,
            'nomor_urut' => $nomor_urut,
        ]);

        \App\Models\StatusTracking::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'status_lama' => 'signed_rektor',
            'status_baru' => 'numbered',
            'catatan' => 'Surat diberi nomor: ' . $nomor_lengkap,
        ]);

        return redirect()->back()->with('success', 'Nomor surat berhasil disimpan: ' . $nomor_lengkap);
    }


    public function preview(Surat $surat)
    {
        if (!in_array($surat->jenis_surat_keluar, ['edaran_rektor', 'sk_rektor', 'surat_tugas', 'nota_dinas'])) {
            abort(404);
        }
        $pdf = Pdf::loadView('surat.templates.' . $surat->jenis_surat_keluar, compact('surat'));
        return $pdf->stream('preview.pdf');
    }
    /**
     * Arsipkan surat (Rektor)
     */
    public function archive(Surat $surat)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya Admin yang dapat mengarsipkan surat.');
        }

        if ($surat->approval_status !== 'numbered' && $surat->approval_status !== 'signed_rektor') {
            return redirect()->back()->with('error', 'Surat harus sudah ditandatangani dan diberi nomor.');
        }

        $surat->update([
            'approval_status' => 'archived',
        ]);

        \App\Models\StatusTracking::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'status_lama' => $surat->getOriginal('approval_status'),
            'status_baru' => 'archived',
            'catatan' => 'Surat diarsipkan',
        ]);

        // Buat record di tabel arsips untuk pengelolaan arsip
        \App\Models\Arsip::create([
            'surat_id' => $surat->id,
            'nomor_surat' => $surat->nomor_surat,
            'status' => 'aktif',
            'tanggal_arsip' => now(),

        ]);

        return redirect()->back()->with('success', 'Surat telah diarsipkan.');
    }

    /**
     * Kembalikan surat ke departemen (tahap final setelah semua proses selesai)
     */
    public function returnToDepartment(Surat $surat)
    {
        if (Auth::user()->role !== 'rektor') {
            return redirect()->back()->with('error', 'Hanya Rektor yang dapat mengembalikan surat.');
        }

        if ($surat->approval_status !== 'archived') {
            return redirect()->back()->with('error', 'Surat harus sudah diarsipkan terlebih dahulu.');
        }

        $surat->update([
            'approval_status' => 'returned',
        ]);

        \App\Models\StatusTracking::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'status_lama' => 'archived',
            'status_baru' => 'returned',
            'catatan' => 'Surat dikembalikan ke departemen (Selesai)',
        ]);

        return redirect()->back()->with('success', 'Surat telah dikembalikan ke departemen.');
    }

    /**
     * Download surat yang sudah ditandatangani secara digital
     */
    public function downloadSigned(Surat $surat)
    {
        // Cek apakah surat sudah ditandatangani
        if (!in_array($surat->approval_status, ['signed_rektor', 'numbered', 'archived', 'returned'])) {
            return redirect()->back()->with('error', 'Surat belum ditandatangani.');
        }

        try {
            if ($surat->jenis_surat_keluar === 'surat_keluar') {
                // Gunakan file yang diupload
                $filePath = public_path($surat->file_path);
                if (!file_exists($filePath)) {
                    return redirect()->back()->with('error', 'File surat tidak ditemukan.');
                }
                $fileContent = file_get_contents($filePath);
                return response($fileContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="preview-' . ($surat->nomor_surat ?? 'draft') . '.pdf"');
            } else {
                // Generate PDF dari template
                $pdf = Pdf::loadView('surat.templates.' . $surat->jenis_surat_keluar, compact('surat'))
                    ->setPaper('a4', 'portrait');
                return $pdf->download((str_replace(['/', '\\'], '-', $surat->nomor_surat ?? 'surat_signed') . '.pdf'));
                // str_replace(['/', '\\'], '-', $surat->nomor_surat ?? 'surat_signed') . '.pdf';
            }
        } catch (\Exception $e) {
            \Log::error('Preview Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal men-generate preview: ' . $e->getMessage());
        }
    }

    /**
     * Verify dan tampilkan info digital signature
     */
    public function verifySignature(Surat $surat)
    {
        $digitalSignature = $surat->digitalSignatures()->first();

        if (!$digitalSignature) {
            return back()->with('error', 'Surat tidak memiliki signature digital.');
        }

        try {
            // Baca file signed yang disimpan
            $fileContent = Storage::get($digitalSignature->signed_file_path);

            // Verify signature
            $isValid = $this->ecdsaService->verify(
                $fileContent,
                $digitalSignature->signature_data,
                $digitalSignature->public_key
            );

            // Get signer info
            $signer = $digitalSignature->signer;

            // Get certificate info (pastikan method ini ada di service Anda)
            $certInfo = $this->ecdsaService->generateCertificateInfo(
                $digitalSignature->public_key,
                $signer->name,
                $digitalSignature->signed_at
            );

            return view('approval.verify-signature', [
                'surat' => $surat,
                'digitalSignature' => $digitalSignature,
                'isValid' => $isValid,
                'signer' => $signer,
                'certInfo' => $certInfo,
            ]);
        } catch (\Exception $e) {
            \Log::error('Signature Verification Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal verify signature: ' . $e->getMessage());
        }
    }

    /**
     * Preview surat untuk Rektor sebelum approval
     */
    public function previewForRektor(Surat $surat)
    {
        if (Auth::user()->role !== 'rektor') {
            abort(403, 'Hanya Rektor yang dapat mem-preview surat.');
        }

        if (!in_array($surat->approval_status, ['approved_wr', 'waiting_rektor_approval', 'approved_rektor', 'rejected_rektor', 'numbered'])) {
            return redirect()->back()->with('error', 'Surat belum siap untuk dipreview.');
        }

        // Generate PDF untuk preview
        try {
            if ($surat->jenis_surat_keluar === 'surat_keluar') {
                // Gunakan file yang diupload
                $filePath = public_path($surat->file_path);
                if (!file_exists($filePath)) {
                    return redirect()->back()->with('error', 'File surat tidak ditemukan.');
                }
                $fileContent = file_get_contents($filePath);
                return response($fileContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="preview-' . ($surat->nomor_surat ?? 'draft') . '.pdf"');
            } else {
                // Generate PDF dari template
                $pdf = Pdf::loadView('surat.templates.' . $surat->jenis_surat_keluar, compact('surat'))
                    ->setPaper('a4', 'portrait');
                return $pdf->stream('preview-' . (str_replace(['/', '\\'], '-', $surat->nomor_surat ?? 'surat_signed') . '.pdf'));
                str_replace(['/', '\\'], '-', $surat->nomor_surat ?? 'surat_signed') . '.pdf';
            }
        } catch (\Exception $e) {
            \Log::error('Preview Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal men-generate preview: ' . $e->getMessage());
        }
    }

    /**
     * Approval Rektor (Pertama Approval - Setuju untuk TTD)
     */
    public function approveRektor(Surat $surat)
    {
        if (Auth::user()->role !== 'rektor') {
            abort(403, 'Hanya Rektor yang dapat menyetujui surat.');
        }

        if (!in_array($surat->approval_status, ['approved_wr', 'waiting_rektor_approval'])) {
            return redirect()->back()->with('error', 'Status surat tidak valid untuk approval.');
        }

        try {
            $surat->update([
                'approval_status' => 'approved_rektor',
                'approved_rektor_by' => Auth::id(),
                'approved_rektor_at' => now(),
            ]);

            \App\Models\StatusTracking::create([
                'surat_id' => $surat->id,
                'user_id' => Auth::id(),
                'status_lama' => 'waiting_rektor_approval',
                'status_baru' => 'approved_rektor',
                'catatan' => 'Review OK, Siap TTD',
            ]);

            return redirect()->route('approval.waitingSignature')
                ->with('success', 'Surat disetujui. Anda dapat menandatangani surat sekarang.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui surat: ' . $e->getMessage());
        }
    }

    /**
     * Reject Rektor (Request Revision)
     */
    public function rejectRektor(Request $request, Surat $surat)
    {
        if (Auth::user()->role !== 'rektor') {
            abort(403, 'Hanya Rektor yang dapat meminta revisi.');
        }

        if (!in_array($surat->approval_status, ['approved_wr', 'waiting_rektor_approval', 'approved_rektor'])) {
            return redirect()->back()->with('error', 'Status surat tidak valid untuk revisi.');
        }

        $request->validate([
            'revision_notes' => 'required|string|max:500',
        ]);

        try {
            $surat->update([
                'approval_status' => 'rejected_rektor',
                'revision_notes' => $request->revision_notes,
                'revision_count' => ($surat->revision_count ?? 0) + 1,
            ]);

            \App\Models\StatusTracking::create([
                'surat_id' => $surat->id,
                'user_id' => Auth::id(),
                'status_lama' => 'waiting_rektor_approval',
                'status_baru' => 'rejected_rektor',
                'catatan' => filter_var($request->revision_notes, FILTER_SANITIZE_SPECIAL_CHARS), // Sanitize input
            ]);

            // Jika sudah approved_rektor, reset approval agar bisa approve lagi nanti
            if ($surat->approved_rektor_at) {
                $surat->update([
                    'approved_rektor_by' => null,
                    'approved_rektor_at' => null,
                    'signed_rektor_by' => null,
                    'signed_rektor_at' => null,
                ]);
            }

            return redirect()->route('approval.waitingSignature')
                ->with('success', 'Surat dikembalikan ke departemen untuk revisi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim revisi: ' . $e->getMessage());
        }
    }
    /**
     * Halaman Tugas Admin (Penomoran & Arsip)
     */
    public function adminTasks()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya Admin yang dapat mengakses halaman ini.');
        }

        // Ambil surat yang sudah TTD Rektor (menunggu nomor)
        $signedSurats = Surat::where('approval_status', 'signed_rektor')
            ->with(['creator', 'signedRektorBy'])
            ->latest()
            ->get();

        // Ambil surat yang sudah dinomori (menunggu arsip)
        $numberedSurats = Surat::where('approval_status', 'numbered')
            ->with(['creator'])
            ->latest()
            ->get();

        return view('approval.admin-tasks', compact('signedSurats', 'numberedSurats'));
    }
}