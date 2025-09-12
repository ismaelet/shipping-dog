<?php

namespace App\Filament\Admin\Resources\Warnings\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Warnings\WarningResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarnings extends ListRecords
{
    protected static string $resource = WarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
