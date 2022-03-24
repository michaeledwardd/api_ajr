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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id('id_mobil');
            $table->unsignedBigInteger('id_mitra')->index();
            $table->string('nama_mobil');
            $table->string('jenis_transmisi');
            $table->string('bahan_bakar');
            $table->string('warna');
            $table->integer('volume_bagasi');
            $table->string('fasilitas');
            $table->string('kategori_aset');
            $table->string('status_ketersediaan');
            $table->string('plat_nomor');
            $table->string('foto_mobil');
            $table->string('tipe_mobil');
            $table->integer('kapasitas');
            $table->double('biaya_sewa');
            $table->date('last_service');
            $table->date('awal_kontrak');
            $table->date('akhir_kontrak');
            $table->integer('nomor_stnk');
            $table->timestamps();
            $table->foreign('id_mitra')->references('id_mitra')->on('mitras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobils');
    }
};
