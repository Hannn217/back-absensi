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
        'daftar_anggota'
    ];

    // Relasi ke model Ketua
    public function ketua()
    {
        return $this->hasOne(KetuaKelas::class, 'nama_kelas');
    }
}
