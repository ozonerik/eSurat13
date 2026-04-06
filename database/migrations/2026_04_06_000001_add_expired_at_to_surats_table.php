<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table): void {
            $table->timestamp('expired_at')->nullable();
            $table->index('expired_at');
        });

        DB::table('surats')
            ->where('status', 'booked')
            ->whereNull('expired_at')
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    $createdAt = $row->created_at ? Carbon::parse($row->created_at) : now();

                    DB::table('surats')
                        ->where('id', $row->id)
                        ->update([
                            'expired_at' => $createdAt->copy()->addDay(),
                            'updated_at' => now(),
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table): void {
            $table->dropIndex(['expired_at']);
            $table->dropColumn('expired_at');
        });
    }
};
