<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TujuanDisposisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Disposisi (many-to-many)
     */
    public function disposisis()
    {
        return $this->belongsToMany(Disposisi::class, 'disposisi_tujuan');
    }

    /**
     * Scope untuk mengambil tujuan yang aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}
