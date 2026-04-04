<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;

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
