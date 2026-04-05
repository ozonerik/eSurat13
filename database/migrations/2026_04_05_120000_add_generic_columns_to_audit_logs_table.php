<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->nullableMorphs('auditable');
            $table->string('menu_label')->nullable();
            $table->string('model_label')->nullable();
            $table->string('record_label')->nullable();
        });

        DB::table('audit_logs')
            ->whereNotNull('surat_id')
            ->orderBy('id')
            ->chunkById(100, function ($auditLogs): void {
                $suratIds = $auditLogs
                    ->pluck('surat_id')
                    ->filter()
                    ->unique()
                    ->values();

                $recordLabels = DB::table('surats')
                    ->whereIn('id', $suratIds)
                    ->pluck('no_surat', 'id');

                foreach ($auditLogs as $auditLog) {
                    DB::table('audit_logs')
                        ->where('id', $auditLog->id)
                        ->update([
                            'auditable_type' => 'App\\Models\\Surat',
                            'auditable_id' => $auditLog->surat_id,
                            'model_label' => 'Surat',
                            'record_label' => $recordLabels[$auditLog->surat_id] ?? null,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropMorphs('auditable');
            $table->dropColumn(['menu_label', 'model_label', 'record_label']);
        });
    }
};