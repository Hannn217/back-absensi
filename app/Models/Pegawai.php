<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    // Nama tabel dalam bentuk string
    protected $table = 'pegawai'; // Pastikan nama tabel sesuai dengan yang ada di database

    protected $fillable = [
        'nama',
        'username',
        'keterangan',
        'alasan',
        'kelas_id',
        'date',
    ];

    // Relasi ke model Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
