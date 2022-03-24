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
        Schema::create('drivers', function (Blueprint $table) {
            $table->string('id_driver')->primary();
            $table->string('nama_driver');
            $table->string('jenis_kelamin');
            $table->string('alamat');
            $table->string('email_driver')->unique();
            $table->string('password');
            $table->string('foto_driver');
            $table->string('status_tersedia');
            $table->double('biaya_sewa_driver');
            $table->string('no_telp');
            $table->date('tgl_lahir');
            $table->float('rerata_rating');
            $table->boolean('mahir_inggris');
            $table->string('upload_sim');
            $table->string('upload_bebas_napza');
            $table->string('upload_sehat_jiwa');
            $table->string('upload_sehat_jasmani');
            $table->string('upload_skck');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};
