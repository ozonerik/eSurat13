<?php

namespace App\Filament\Resources\AuditLogs\Pages;

use App\Filament\Resources\AuditLogs\AuditLogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewAuditLog extends ViewRecord
{
    protected static string $resource = AuditLogResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            AuditLogResource::getUrl('index') => 'Audit Log',
            '#' => 'Detail',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
