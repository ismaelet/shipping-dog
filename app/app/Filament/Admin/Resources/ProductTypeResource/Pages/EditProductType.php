<?php

namespace App\Filament\Admin\Resources\ProductTypeResource\Pages;

use App\Filament\Admin\Resources\ProductTypeResource;
use App\Helpers\FileHelper;
use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Product\ProductType;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductType extends EditRecord {
	protected static string $resource = ProductTypeResource::class;

	protected function getHeaderActions(): array {
		return [
			Actions\DeleteAction::make()
				->before(fn(ProductType $record) => ProductTypeResource::deleteAction($record))
		];
	}

	protected function afterSave(): void {
		PreviewHelper::deleteByProductType($this->record);
	}
}
