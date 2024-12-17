<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'daftar_anggota',
    ];

    // Relasi dengan users
    public function users()
    {
        return $this->hasMany(User::class, 'nama_kelas', 'nama_kelas');
    }

    // Sinkronisasi daftar_anggota dengan username user
    public function syncDaftarAnggota()
    {
        // Mengambil username dari semua users yang terhubung dan menyimpannya dalam kolom JSON
        $this->daftar_anggota = $this->users->pluck('username')->toJson();
        $this->save();
    }
}
