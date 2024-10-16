<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'pengajuan_cuti';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'user_id',
        'nama',
        'nama_kelas',
        'status',
        'keterangan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    // Definisikan relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
