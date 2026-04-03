<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('counter_surats', function (Blueprint $table) {
            $table->foreignId('kategori_surat_id')
                ->nullable()
                ->after('id')
                ->constrained('kategori_surats')
                ->nullOnDelete();

            $table->unique(['kategori_surat_id', 'tahun']);
        });

        DB::table('counter_surats')
            ->join('jenis_surats', 'jenis_surats.id', '=', 'counter_surats.jenis_surat_id')
            ->whereNull('counter_surats.kategori_surat_id')
            ->select('counter_surats.id as counter_id', 'jenis_surats.kategori_surat_id')
            ->orderBy('counter_surats.id')
            ->get()
            ->each(function (object $row): void {
                DB::table('counter_surats')
                    ->where('id', $row->counter_id)
                    ->update(['kategori_surat_id' => $row->kategori_surat_id]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counter_surats', function (Blueprint $table) {
            $table->dropUnique(['kategori_surat_id', 'tahun']);
            $table->dropForeign(['kategori_surat_id']);
            $table->dropColumn('kategori_surat_id');
        });
    }
};
