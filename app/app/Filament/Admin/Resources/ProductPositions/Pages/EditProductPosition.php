<?php

namespace App\Filament\Admin\Resources\ProductPositions\Pages;

use App\Filament\Admin\Resources\ProductPositions\ProductPositionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductPosition extends EditRecord
{
    protected static string $resource = ProductPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
