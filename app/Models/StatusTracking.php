<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'user_id',
        'status_lama',
        'status_baru',
        'catatan',
    ];

    // Relasi
    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}