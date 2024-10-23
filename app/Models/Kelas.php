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

    protected $rules = [
        'nama_kelas' => 'required|string|unique:kelas,nama_kelas|max:255',
    ];

    // Relasi ke model Ketua
    public function ketua()
    {
        return $this->hasOne(KetuaKelas::class, 'nama_kelas');
    }
    
    public function users()
    {
        return $this->hasMany(User::class, 'nama_kelas', 'nama_kelas');
    }
}
