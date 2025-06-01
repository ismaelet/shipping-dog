<?php

namespace App\Filament\Admin\Resources\ProductPositionResource\Pages;

use App\Filament\Admin\Resources\ProductPositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductPositions extends ListRecords
{
    protected static string $resource = ProductPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
