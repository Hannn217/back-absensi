<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',        // Foreign key ke user
        'nama_kelas',     // Nama kelas (jika ada)
        'nama',           // Nama pengaju
        'tanggal_mulai',  // Tanggal mulai cuti
        'tanggal_selesai',// Tanggal selesai cuti
        'status',         // Status pengajuan cuti
        'keterangan',     // Keterangan pengajuan
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke model Kelas (opsional, jika ada tabel kelas)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'nama_kelas', 'nama_kelas');
    }
}
