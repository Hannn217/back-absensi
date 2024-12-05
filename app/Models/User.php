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

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'nomor_hp',
        'jabatan',
        'nama_kelas',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke model Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'nama_kelas', 'nama_kelas');
    }

    // Tambahkan sinkronisasi otomatis menggunakan event
    protected static function boot()
    {
        parent::boot();

        // Saat user disimpan (tambah/update)
        static::saved(function ($user) {
            if ($user->nama_kelas) {
                $kelas = Kelas::where('nama_kelas', $user->nama_kelas)->first();
                if ($kelas) {
                    $kelas->syncDaftarAnggota(); // Sinkronisasi daftar_anggota
                }
            }
        });

        // Saat user dihapus
        static::deleted(function ($user) {
            if ($user->nama_kelas) {
                $kelas = Kelas::where('nama_kelas', $user->nama_kelas)->first();
                if ($kelas) {
                    $kelas->syncDaftarAnggota(); // Sinkronisasi daftar_anggota
                }
            }
        });
    }
}
