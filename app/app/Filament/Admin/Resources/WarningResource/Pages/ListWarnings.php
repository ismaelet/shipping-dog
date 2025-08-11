<?php

namespace App\Filament\Admin\Resources\WarningResource\Pages;

use App\Filament\Admin\Resources\WarningResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarnings extends ListRecords
{
    protected static string $resource = WarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
