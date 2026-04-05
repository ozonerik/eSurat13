<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AppInfoWidget extends Widget
{
    protected static ?string $heading = 'Informasi Aplikasi';

    protected string $view = 'filament.widgets.app-info-widget';

    public function getAppName(): string
    {
        return config('app.name', 'eSurat13');
    }

    public function getAppVersion(): string
    {
        return config('app.version', '1.0.0');
    }

    public function getChangelog(): array
    {
        return [
            [
                'version' => '1.0.0',
                'date' => '3 April 2026',
                'changes' => [
                    'Rilis pertama aplikasi eSurat13',
                    'Fitur penomoran surat otomatis',
                    'Workflow approval dan tanda tangan digital',
                    'Audit trail lengkap',
                    'Notifikasi Telegram dengan queue system',
                    'Widget pemantauan kesehatan queue',
                ],
            ],
        ];
    }
}
