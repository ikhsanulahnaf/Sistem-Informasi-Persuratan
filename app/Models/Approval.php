<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'approver_id',
        'catatan',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relasi
    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}