<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang diizinkan untuk diisi secara Mass Assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Wajib ada agar role (owner/staff/customer) bisa disimpan
    ];

    /**
     * Kolom yang disembunyikan saat data diubah ke JSON/Array (keamanan).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi tipe data otomatis.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi: 1 User (Pelanggan/Staff) memiliki banyak transaksi Penjualan.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}