<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetuaKelasTable extends Migration
{
    public function up()
    {
        Schema::create('ketua', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreign('username')->references('username')->on('users')->onDelete('cascade');            // Nama ketua
            $table->string('username'); // Username ketua
            $table->enum('keterangan', ['hadir', 'izin', 'sakit']); // Keterangan absensi
            $table->string('alasan')->nullable(); 
            $table->string('nama_kelas'); // Nama ketua
            $table->foreign('nama_kelas')->references('nama_kelas')->on('kelas')->onDelete('cascade'); // Asal kelas, relasi dengan nama_kelas dari tabel kelas
            $table->date('date'); // Tanggal absensi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ketua');
    }
}
