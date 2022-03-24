<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_shifts', function (Blueprint $table) {
            $table->id('id_detail_shift');
            $table->unsignedBigInteger('id_pegawai')->index();
            $table->unsignedBigInteger('id_jadwal')->index();
            $table->integer('jumlah_pegawai');
            $table->timestamps();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_shifts');
    }
};
