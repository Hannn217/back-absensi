<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Kelas; // Import model Kelas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function __construct()
    {
        // Middleware auth untuk memastikan hanya pengguna yang sudah login bisa mengakses method absen
        $this->middleware('auth')->only(['absen']);
    }

    // Method untuk melakukan absensi oleh pegawai yang sudah login
    public function absen(Request $request)
    {
        // Validasi input dari request
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'alasan' => 'required|string|max:255',
            'date' => 'required|date',
            'nama_kelas' => 'required|string|max:255'
        ]);

        // Mendapatkan pengguna yang sedang login
        $user = Auth::user();

        // Cari kelas berdasarkan nama_kelas
        $kelas = Kelas::where('nama_kelas', $request->nama_kelas)->first();

        // Jika kelas tidak ditemukan, berikan respon error
        if (!$kelas) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kelas tidak ditemukan'
            ], 404);
        }

        // Simpan absensi pegawai
        $pegawai = Pegawai::create([
            'nama' => $user->nama,
            'username' => $user->username,
            'keterangan' => $request->keterangan,
            'alasan' => $request->alasan,
            'date' => $request->date,
            'nama_kelas' => $kelas->nama_kelas // Ambil nama_kelas dari tabel Kelas
        ]);

        // Mengembalikan respon sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil absen',
            'data' => $pegawai
        ], 201);
    }
}
