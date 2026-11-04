<?php

namespace App\Filament\Admin\Resources\ProductPositions\Pages;

use App\Filament\Admin\Resources\ProductPositions\ProductPositionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductPosition extends CreateRecord
{
    protected static string $resource = ProductPositionResource::class;
}
