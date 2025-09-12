<?php

namespace App\Filament\Admin\Resources\Warnings\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\Warnings\WarningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarning extends EditRecord
{
    protected static string $resource = WarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
