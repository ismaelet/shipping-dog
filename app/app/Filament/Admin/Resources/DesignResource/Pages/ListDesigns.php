<?php

namespace App\Filament\Admin\Resources\DesignResource\Pages;

use App\Filament\Admin\Resources\DesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDesigns extends ListRecords
{
    protected static string $resource = DesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
