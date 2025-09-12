<?php

namespace App\Filament\Admin\Resources\Designs\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\Designs\DesignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesign extends EditRecord
{
    protected static string $resource = DesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
