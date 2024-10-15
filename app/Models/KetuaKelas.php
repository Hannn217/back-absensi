<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetuaKelas extends Model
{
    use HasFactory;

    protected $table = 'ketua';

    protected $fillable = [
        'nama', 'username', 'keterangan', 'alasan', 'kelas_id'
    ];

    // Relasi ke model Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'nama_kelas');
    }
}
