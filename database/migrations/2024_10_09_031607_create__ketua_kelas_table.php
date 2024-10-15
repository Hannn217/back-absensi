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
            $table->string('nama'); // Nama ketua
            $table->string('username')->unique(); // Username ketua
            $table->enum('keterangan', ['hadir', 'izin', 'sakit']); // Keterangan absensi
            $table->string('alasan')->nullable(); 
            $table->foreignId('nama_kelas')->constrained('kelas')->onDelete('cascade'); // Asal kelas, relasi dengan nama_kelas dari tabel kelas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ketua');
    }
}
