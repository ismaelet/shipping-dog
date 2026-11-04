<?php

namespace App\Filament\Admin\Resources\Designs\Pages;

use App\Filament\Admin\Resources\Designs\DesignResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDesigns extends ListRecords
{
    protected static string $resource = DesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
