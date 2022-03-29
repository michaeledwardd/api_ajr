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
        Schema::create('customer', function (Blueprint $table) {
            $table->string('id_customer')->primary();
            $table->string('nama_customer');
            $table->string('alamat_customer');
            $table->date('tgl_lahir');
            $table->string('jenis_kelamin');
            $table->string('email_customer')->unique();
            $table->string('no_telp');
            $table->string('upload_berkas');
            $table->string('status_berkas');
            $table->integer('nomor_kartupengenal');
            $table->integer('no_sim')->nullable();
            $table->string('asal_customer');
            $table->string('password');
            $table->integer('usia_customer');
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
        Schema::dropIfExists('customers');
    }
};
