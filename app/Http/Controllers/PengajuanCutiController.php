<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator; // Make sure to include the Validator facade

class PengajuanCutiController extends Controller
{
    // Fungsi untuk mengajukan cuti
    public function pengajuan(Request $request)
    {
        // Validasi input pengguna dan simpan hasilnya ke dalam $validatedData
        $request->validate([
            'users_id' => 'required|string|max:255|exists:users,id', // 'exists' to check if user exists
            'nama_kelas' => 'required|string|max:255|exists:kelas,nama_kelas', // Changed 'unique' to 'exists'
            'nama' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai', // Ensure end date is after start date
        ]);

        // Fetch user based on the validated 'users_id'
        $user = User::findOrFail($validatedData['id']); // Use 'findOrFail' to get the user

        // Buat pengajuan cuti
        $pengajuancuti = PengajuanCuti::create([
            'users_id' => $validatedData['users_id'], // Use validated data
            'nama_kelas' => $validatedData['nama_kelas'],
            'nama' => $validatedData['nama'],
            'tanggal_mulai' => $validatedData['tanggal_mulai'],
            'tanggal_selesai' => $validatedData['tanggal_selesai'],
            'status' => 'SedangDiProses',
            'keterangan' => $validatedData['keterangan'],
        ]);

        // Mengembalikan respon sederhana
        return response()->json(['message' => 'Cuti berhasil diajukan!'], 201);
    }
}
