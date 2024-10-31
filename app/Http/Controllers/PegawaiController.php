<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
<<<<<<< HEAD
    /**
     * Menampilkan profil pengguna yang sedang login.
     */
=======
    // Menampilkan semua data pegawai
    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai, 201); // Mengembalikan semua pegawai dengan status 201
    }

>>>>>>> f7071e669077b0c66a299e8c3dc36e581c57a9c8
    public function profile()
    {
        // Ambil pengguna yang sedang login
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan atau belum login',
            ], 404);
        }

        // Kembalikan data profil pengguna
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'username' => $user->username,
                'email' => $user->email,
                'nomor_hp' => $user->nomor_hp,
                'jabatan' => $user->jabatan,
                'nama_kelas' => $user->nama_kelas,
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 200);
    }

<<<<<<< HEAD
    /**
     * Menampilkan semua data pegawai.
     */
    public function index()
    {
        $pegawai = Pegawai::all();

        return response()->json([
            'status' => 'success',
            'data' => $pegawai,
        ], 200); // Mengembalikan semua pegawai dengan status 200
    }

    /**
     * Menyimpan absen pegawai ke dalam database.
     */
=======
    // Menyimpan absen ke database
>>>>>>> f7071e669077b0c66a299e8c3dc36e581c57a9c8
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'keterangan' => 'required|in:hadir,izin,sakit',
            'alasan' => 'required|string|max:255',
            'nama_kelas' => 'required|exists:kelas,nama_kelas', // Pastikan nama_kelas valid
            'date' => 'required|date',
        ]);

        // Simpan data absen
        $pegawai = Pegawai::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'keterangan' => $request->keterangan,
            'alasan' => $request->alasan,
            'nama_kelas' => $request->nama_kelas,
            'date' => $request->date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil melakukan absen.',
            'data' => $pegawai,
        ], 201); // Mengembalikan respons dengan status 201
    }

    /**
     * Menghapus data absen pegawai dari database.
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Absen pegawai tidak ditemukan.',
            ], 404); // Mengembalikan respons dengan status 404 jika absen tidak ditemukan
        }

        $pegawai->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Absen pegawai berhasil dihapus.',
        ], 200); // Mengembalikan respons dengan status 200
    }
}
