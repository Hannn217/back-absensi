<?php

namespace App\Http\Controllers;

use App\Models\KetuaKelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KetuaKelasController extends Controller
{
    // Menampilkan semua ketua kelas
    public function index()
    {
        $ketuaKelas = KetuaKelas::all();
        return response()->json([
            'status' => 'success',
            'data' => $ketuaKelas
        ]);
    }

    // Menambahkan ketua kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:ketua_kelas',
            'kelas' => 'required|string|max:255',
            'keterangan' => 'required|in:hadir,izin,sakit',
            'alasan' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        $ketuaKelas = KetuaKelas::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'absensi Ketua kelas berhasil ditambahkan',
            'data' => $ketuaKelas
        ], 201);
    }

    // Menampilkan detail ketua kelas tertentu
    public function show($username)
    {
        $ketuaKelas = KetuaKelas::find($username);
        if (!$ketuaKelas) {
            return response()->json(['message' => 'Ketua kelas tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $ketuaKelas
        ]);
    }

    // Memperbarui ketua kelas tertentu
    public function update(Request $request, $username)
    {
        // Cari ketua kelas berdasarkan username
        $ketuaKelas = KetuaKelas::where('username', $username)->first();
        
        // Jika ketua kelas tidak ditemukan
        if (!$ketuaKelas) {
            return response()->json(['message' => 'Ketua kelas tidak ditemukan'], 404);
        }
    
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            // Validasi unique dengan pengecualian untuk username yang sedang diupdate
            'username' => ['required', 'string', 'max:255', Rule::unique('ketua_kelas')->ignore($ketuaKelas->id)],
            'kelas' => 'required|string|max:255',
            'keterangan' => 'required|in:hadir,izin,sakit',
            'alasan' => 'nullable|string|max:255', // Alasan hanya wajib jika izin atau sakit
            'date' => 'required|date',
        ]);
    
        // Update data ketua kelas
        $ketuaKelas->update($request->all());
    
        // Response sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Ketua kelas berhasil diperbarui',
            'data' => $ketuaKelas
        ], 200);
    }
    
}
