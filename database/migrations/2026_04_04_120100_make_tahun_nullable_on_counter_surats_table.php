<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE counter_surats ALTER COLUMN tahun DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('counter_surats')
            ->whereNull('tahun')
            ->update(['tahun' => (int) now()->format('Y')]);

        DB::statement('ALTER TABLE counter_surats ALTER COLUMN tahun SET NOT NULL');
    }
};