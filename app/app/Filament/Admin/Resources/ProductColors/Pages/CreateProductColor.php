<?php

namespace App\Filament\Admin\Resources\ProductColors\Pages;

use App\Filament\Admin\Resources\ProductColors\ProductColorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductColor extends CreateRecord
{
    protected static string $resource = ProductColorResource::class;
}
