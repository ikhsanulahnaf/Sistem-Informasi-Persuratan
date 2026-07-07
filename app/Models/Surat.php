<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'isi_ringkas',
        'pengirim',
        'penerima',
        'jenis',
        'file_path',
        'approval_status',
        'jenis_surat_keluar',
        'tembusan',
        'created_by',
        'paraf_wr_by',
        'paraf_wr_at',
        'approved_rektor_by',
        'approved_rektor_at',
        'signed_rektor_by',
        'signed_rektor_at',
        'nomor_urut',
        'revision_count',
        'revision_notes',
        'pertimbangan',
        'dasar',
        'untuk',
        'menimbang',
        'menetapkan',
        'tembusan_surat_id',
        'lampiran_path',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'paraf_wr_at' => 'datetime',
        'approved_rektor_at' => 'datetime',
        'signed_rektor_at' => 'datetime',
        'tembusan' => 'array',
        'pertimbangan' => 'array',
        'untuk' => 'array',
        'menimbang' => 'array',
        'menetapkan' => 'array',
    ];

    // Relasi
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parafBy()
    {
        return $this->belongsTo(User::class, 'paraf_wr_by');
    }

    public function approvedByRektor()
    {
        return $this->belongsTo(User::class, 'approved_rektor_by');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_rektor_by');
    }

    public function signedRektorBy()
    {
        return $this->belongsTo(User::class, 'signed_rektor_by');
    }

    public function approval()
    {
        return $this->hasOne(Approval::class);
    }

    public function disposisi()
    {
        return $this->hasOne(Disposisi::class);
    }

    public function arsip()
    {
        return $this->hasOne(Arsip::class);
    }

    public function digitalSignatures()
    {
        return $this->hasMany(DigitalSignature::class);
    }

    public function statusTrackings()
    {
        return $this->hasMany(StatusTracking::class);
    }

    public function digitalSignature()
    {
        return $this->hasOne(DigitalSignature::class);
    }
    public function getNomorFormatAttribute()
    {
        if ($this->jenis !== 'keluar')
            return null;

        $bulan = $this->tanggal_surat->format('n'); // 1-12
        $tahun = $this->tanggal_surat->format('Y');

        return match ($this->jenis_surat_keluar) {
            'sk_rektor' => "{nomor}/Kept-lTI/{$bulan}/{$tahun}",
            'edaran_rektor' => "{nomor}/E/R-ITI/{$bulan}/{$tahun}",
            'surat_tugas' => "{nomor}/R-ITI/{$bulan}/{$tahun}",
            'nota_dinas' => "{nomor}/INT/R-ITI/{$bulan}/{$tahun}",
            default => "{nomor}/KL/{$bulan}/{$tahun}",
        };
    }
    public function tembusanSurat()
    {
        return $this->belongsTo(Surat::class, 'tembusan_surat_id');
    }
}