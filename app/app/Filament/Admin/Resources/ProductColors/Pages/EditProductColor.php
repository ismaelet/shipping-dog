<?php

namespace App\Filament\Admin\Resources\ProductColors\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\ProductColors\ProductColorResource;
use App\Helpers\FileHelper;
use App\Models\Ecommerce\Product\ProductType;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProductColor extends EditRecord {

    protected static string $resource = ProductColorResource::class;

    /**
     * Handle the form submission for updating a record.
     * 
     * @param Model $record The record to update.
     * @param array $data The data to update the record with.
     * @return Model The updated record.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model {
        $record->update($data);

        ProductType::all()->each(
            function (ProductType $productType) use ($record) {
                $files = glob(FileHelper::storagePath('products/' . $productType->tag . '/*-' . $record->tag . '-*'));

                array_map(
                    function ($file) {
                        $file = str_replace(FileHelper::storagePath(), '', $file);
                        FileHelper::delete($file);
                    },
                    $files
                );
            }
        );

        return $record;
    }

    protected function getHeaderActions(): array {
        return [
            DeleteAction::make(),
        ];
    }
}
