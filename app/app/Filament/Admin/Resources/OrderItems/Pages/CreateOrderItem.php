<?php

namespace App\Filament\Admin\Resources\OrderItems\Pages;

use App\Filament\Admin\Resources\OrderItems\OrderItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderItem extends CreateRecord
{
    protected static string $resource = OrderItemResource::class;
}
