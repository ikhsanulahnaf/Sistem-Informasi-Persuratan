<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DigitalSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'signer_id',
        'algorithm',
        'public_key',
        'signature_data',
        'signed_file_path',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    // Relasi
    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function signer()
    {
        return $this->belongsTo(User::class, 'signer_id');
    }

}