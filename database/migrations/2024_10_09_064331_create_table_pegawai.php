<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama pegawai
            $table->string('username'); // Username pegawai (relasi dengan kolom daftar_anggota di tabel kelas)
            $table->enum('keterangan', ['hadir', 'izin', 'sakit']); // Keterangan absensi
            $table->string('alasan')->nullable();
            $table->string('nama_kelas');
            $table->foreignId('nama_kelas')->constrained('kelas')->onDelete('cascade'); // Relasi ke tabel kelas (kolom nama_kelas)
            $table->date('date'); // Tanggal absensi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pegawai');
    }
};
