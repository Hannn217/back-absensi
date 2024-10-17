<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;

    // Specify the table name if it differs from the pluralized model name
    protected $table = 'pengajuan_cuti'; // Change if your table has a different name

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'user_id',      // Foreign key for the user
        'nama',         // Name of the applicant
        'nama_kelas',   // Name of the class
        'tanggal_mulai', // Start date of the leave
        'tanggal_selesai', // End date of the leave
        'status',       // Status of the leave request
        'keterangan',   // Additional information about the leave
    ];

    // Define the relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Foreign key in the current model
    }

    // You can add more relationships as needed (e.g., kelas)
}
