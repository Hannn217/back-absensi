<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    // Menampilkan semua data pegawai
    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai, 200); // Mengembalikan semua pegawai dengan status 200
    }

    // Menampilkan form untuk membuat pegawai baru
    public function create()
    {
        return view('pegawai.create');
    }

    // Menyimpan data pegawai baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'keterangan' => 'required|in:hadir,izin,sakit',
            'alasan' => 'nullable|string|max:255',
            'nama_kelas' => 'required|exists:kelas,id',
            'date' => 'required|date',
        ]);

        $pegawai = Pegawai::create($request->all());

        return response()->json([
            'message' => 'Pegawai berhasil ditambahkan.',
            'pegawai' => $pegawai,
        ], 201); // Mengembalikan respons dengan status 200
    }

    // Menghapus data pegawai dari database
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return response()->json([
            'message' => 'Data pegawai berhasil dihapus.',
        ], 200); // Mengembalikan respons dengan status 200
    }
}
