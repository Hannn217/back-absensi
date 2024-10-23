<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nomor_hp');
            $table->enum('jabatan', ['Super Admin', 'System Admin', 'Ketua Kelas', 'Pegawai'])->default('Pegawai');
            // Menambahkan kolom nama_kelas sebagai foreign key
            $table->string('nama_kelas')->nullable();
            $table->foreign('nama_kelas')->references('nama_kelas')->on('kelas')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
