<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko')->unique();
            $table->string('jenis_toko');
            $table->longText('alamat_lengkap');
            $table->string('email_toko')->nullable();
            $table->string('telephone_toko')->nullable();
            $table->string('npwp')->unique()->nullable();
            $table->string('kode_provinsi');
            $table->string('kode_kota');
            $table->string('kode_kecamatan');
            $table->string('kode_kelurahan');
            $table->string('kode_pos');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            $table->foreign('kode_provinsi')->references('kode_provinsi')->on('masterdata_provinsi');
            $table->foreign('kode_kota')->references('kode_kota')->on('masterdata_kota');
            $table->foreign('kode_kecamatan')->references('kode_kecamatan')->on('masterdata_kecamatan');
            $table->foreign('kode_kelurahan')->references('kode_kelurahan')->on('masterdata_kelurahan');
        
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toko');
    }
};
