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
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 20)->unique();
            $table->string('nss', 20)->nullable();
            $table->string('nama_sekolah');
            $table->text('visi_sekolah')->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->string('kota_kab')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kcd_wilayah', 20)->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('telp', 50)->nullable();
            $table->string('kodepos', 10)->nullable();
            $table->string('akreditasi', 5)->nullable();
            $table->string('logo_sekolah')->nullable();
            $table->string('logo_provinsi')->nullable();
            $table->string('stamp_sekolah')->nullable();
            $table->boolean('show_stamp')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
