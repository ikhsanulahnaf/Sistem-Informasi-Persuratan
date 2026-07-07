<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class EcdsaKeyPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'public_key',
        'private_key',
        'algorithm',
        'curve',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    /**
     * Encrypt private key sebelum save
     */
    public function setPrivateKeyAttribute($value)
    {
        $this->attributes['private_key'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt private key ketika retrieve
     */
    public function getPrivateKeyAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * Relationship ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
