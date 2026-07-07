<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi
    public function surats()
    {
        return $this->hasMany(Surat::class, 'created_by');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'approver_id');
    }

    public function disposisis()
    {
        return $this->hasMany(Disposisi::class, 'disposer_id');
    }

    public function digitalSignatures()
    {
        return $this->hasMany(DigitalSignature::class, 'signer_id');
    }

    public function statusTrackings()
    {
        return $this->hasMany(StatusTracking::class);
    }
}