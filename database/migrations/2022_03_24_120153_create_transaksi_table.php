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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('id_transaksi')->primary();
            $table->string('id_customer')->index();
            $table->unsignedBigInteger('id_mobil')->index();
            $table->unsignedBigInteger('id_pegawai')->index();
            $table->string('id_driver')->index()->nullable();
            $table->unsignedBigInteger('id_promo')->index()->nullable();
            $table->date('tgl_transaksi');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->time('waktu_kembali');
            $table->date('tgl_selesai_pinjam')->nullable();
            $table->time('waktu_selesai_pinjam')->nullable();
            $table->string('jenis_peminjaman');
            $table->boolean('cek_terlambat')->nullable();
            $table->double('total_denda')->nullable();
            $table->double('total_biaya_pinjam');
            $table->double('biaya_denda')->nullable();
            $table->double('total_sewa_driver')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->double('subtotal_all')->nullable();
            $table->string('status_transaksi')->nullable();
            $table->string('metode_bayar')->nullable();
            $table->integer('rating_perform_driver')->nullable();
            $table->integer('rating_perform_ajr')->nullable();
            $table->timestamps();
            $table->foreign('id_customer')->references('id_customer')->on('customer');
            $table->foreign('id_mobil')->references('id_mobil')->on('mobil');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai');
            $table->foreign('id_driver')->references('id_driver')->on('driver');
            $table->foreign('id_promo')->references('id_promo')->on('promo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};
