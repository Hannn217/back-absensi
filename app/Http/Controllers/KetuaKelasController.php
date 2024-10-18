<?php

namespace App\Http\Controllers;

use App\Models\KetuaKelas;
use Illuminate\Http\Request;

class KetuaKelasController extends Controller
{
    // Menampilkan semua data ketua
    public function index()
    {
        $ketua = KetuaKelas::all();
        return response()->json($ketua, 200);
    }

    // Menampilkan form untuk membuat ketua baru
    public function create()
    {
        return view('ketua.create');
    }

    // Menyimpan data ketua baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'keterangan' => 'required|in:hadir,izin,sakit',
            'alasan' => 'required|string|max:255',
            'nama_kelas' => 'required|exists:kelas,nama_kelas',
            'date' => 'required|date',
        ]);

        $ketua = KetuaKelas::create($request->all());

        return response()->json([
            'message' => 'KetuaKelas berhasil melakukan absen.',
            'ketua' => $ketua,
        ], 201);
    }

    // Menghapus data ketua dari database berdasarkan username
    public function destroy($username)
    {
        $ketua = KetuaKelas::where('username', $username)->firstOrFail();
        $ketua->delete();

        return response()->json([
            'message' => 'Data ketua berhasil dihapus.',
        ], 200);
    }
}
