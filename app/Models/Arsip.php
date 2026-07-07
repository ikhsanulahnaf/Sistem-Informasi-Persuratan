<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arsip extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'kode_arsip',
        'lokasi_fisik',
        'tanggal_arsip',
    ];

    protected $casts = [
        'tanggal_arsip' => 'date',
    ];

    // Relasi
    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}