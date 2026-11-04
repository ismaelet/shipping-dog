<?php

namespace App\Filament\Admin\Resources\ProductPositions\Pages;

use App\Filament\Admin\Resources\ProductPositions\ProductPositionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductPositions extends ListRecords
{
    protected static string $resource = ProductPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
