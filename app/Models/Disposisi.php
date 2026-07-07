<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disposisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'disposer_id',
        'instruksi',
        'tujuan_disposisi',
        'disposed_at',
        'sifat_disposisi',
    ];

    protected $casts = [
        'disposed_at' => 'datetime',
    ];

    // Relasi
    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function disposer()
    {
        return $this->belongsTo(User::class, 'disposer_id');
    }

    public function tujuans()
    {
        return $this->belongsToMany(TujuanDisposisi::class, 'disposisi_tujuan')
            ->withPivot('created_at', 'updated_at')
            ->orderBy('urutan');
    }
}