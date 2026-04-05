<?php

namespace App\Providers;

use App\Models\CounterSurat;
use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Sekolah;
use App\Models\Surat;
use App\Models\TelegramLog;
use App\Models\User;
use App\Observers\ModelAuditObserver;
use App\Observers\SuratObserver;
use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Surat::observe([
            ModelAuditObserver::class,
            SuratObserver::class,
        ]);
        User::observe(ModelAuditObserver::class);
        Sekolah::observe(ModelAuditObserver::class);
        JenisSurat::observe(ModelAuditObserver::class);
        KategoriSurat::observe(ModelAuditObserver::class);
        CounterSurat::observe(ModelAuditObserver::class);
        TelegramLog::observe(ModelAuditObserver::class);
        Role::observe(ModelAuditObserver::class);
        Permission::observe(ModelAuditObserver::class);

        DatePicker::configureUsing(function (DatePicker $component): void {
            $component
                ->displayFormat('d M Y')
                ->native(false);
        });

        // Also apply to DateTimePickers if needed
        DateTimePicker::configureUsing(function (DateTimePicker $component): void {
            $component
                ->displayFormat('d M Y')
                ->native(false);
        });
    }
}
