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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->restrictOnDelete();
            $table->foreignId('pembuat_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('approver_id')->constrained('users')->restrictOnDelete();
            $table->string('no_surat')->unique();
            $table->string('perihal');
            $table->string('tujuan')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->enum('status', ['draft', 'booked', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'expired'])->default('draft');
            $table->string('surat_file_path')->nullable();
            $table->string('verification_token')->nullable()->unique();
            $table->text('rejection_note')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
