<?php

namespace App\Filament\Admin\Resources\CustomerPhotos\Pages;

use App\Filament\Admin\Resources\CustomerPhotos\CustomerPhotoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerPhoto extends CreateRecord
{
    protected static string $resource = CustomerPhotoResource::class;

    protected function afterCreate(): void
    {
        $tempImagePath = storage_path('app/public/users/testimonials/temp.webp');
        $newImagePath = storage_path('app/public/users/testimonials/'.$this->record->id.'.webp');

        if (file_exists($tempImagePath)) {
            rename($tempImagePath, $newImagePath);
        }
    }
}
