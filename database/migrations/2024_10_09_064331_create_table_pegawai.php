<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama pegawai
            $table->string('username'); // Username pegawai (relasi dengan kolom daftar_anggota di tabel kelas)
            $table->foreign('username')->references('username')->on('users')->onDelete('cascade');
            $table->enum('keterangan', ['hadir', 'izin', 'sakit']); // Keterangan absensi
            $table->string('alasan')->nullable();
            $table->string('nama_kelas')->nullable();
            $table->foreign('nama_kelas')->references('nama_kelas')->on('kelas')->onDelete('cascade');
            $table->date('date'); // Tanggal absensi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pegawai');
    }
};
