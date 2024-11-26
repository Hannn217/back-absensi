<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Isi fillable jika diperlukan
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'nomor_hp',
        'jabatan',
        'nama_kelas'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi dengan Kelas (Jika Ketua Kelas terkait dengan Kelas tertentu)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'nama_kelas', 'nama_kelas');
    }

    /**
     * Relasi untuk menjadi ketua kelas.
     */
    public function ketuaKelas()
    {
        return $this->hasOne(Kelas::class, 'ketua_kelas_id');
    }
}
