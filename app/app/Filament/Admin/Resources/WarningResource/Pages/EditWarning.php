<?php

namespace App\Filament\Admin\Resources\WarningResource\Pages;

use App\Filament\Admin\Resources\WarningResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarning extends EditRecord
{
    protected static string $resource = WarningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
