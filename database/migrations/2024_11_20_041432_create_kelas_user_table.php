<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kelas_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id'); // Relasi ke kelas
            $table->unsignedBigInteger('user_id');  // Relasi ke pengguna
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas_user');
    }
};
