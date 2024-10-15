<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasTable extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama_kelas')->unique(); // Nama kelas harus unik
            $table->json('daftar_anggota'); // Daftar anggota dalam bentuk JSON
            $table->timestamps(); // Kolom created_at dan updated_at otomatis diisi oleh Laravel
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
}
