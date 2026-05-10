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
            $table->unsignedSmallInteger('tahun')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('counter_surats')
            ->whereNull('tahun')
            ->update(['tahun' => (int) now()->format('Y')]);

        Schema::table('counter_surats', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun')->nullable(false)->change();
        });
    }
};