<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang bisa diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'identity_type',
        'identity_file',
        'verified',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi (misalnya untuk API).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi otomatis tipe data untuk atribut tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified' => 'boolean',
        'password' => 'hashed', // Laravel >= 10
    ];
}
