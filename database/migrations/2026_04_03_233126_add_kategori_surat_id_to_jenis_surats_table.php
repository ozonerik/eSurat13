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
        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->foreignId('kategori_surat_id')
                ->nullable()
                ->after('id')
                ->constrained('kategori_surats')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_surats', function (Blueprint $table) {
            $table->dropForeign(['kategori_surat_id']);
            $table->dropColumn('kategori_surat_id');
        });
    }
};
