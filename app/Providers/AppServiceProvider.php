<?php

namespace App\Providers;

use App\Models\CounterSurat;
use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Sekolah;
use App\Models\Surat;
use App\Models\User;
use App\Observers\ModelAuditObserver;
use App\Observers\SuratObserver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\ServiceProvider;
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
        Surat::observe(SuratObserver::class);
        User::observe(ModelAuditObserver::class);
        KategoriSurat::observe(ModelAuditObserver::class);
        JenisSurat::observe(ModelAuditObserver::class);
        Sekolah::observe(ModelAuditObserver::class);
        CounterSurat::observe(ModelAuditObserver::class);
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
