<?php

namespace App\Filament\Admin\Resources\ProductTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\ProductTypes\ProductTypeResource;
use App\Helpers\FileHelper;
use App\Helpers\PreviewHelper;
use App\Models\Ecommerce\Product\ProductType;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductType extends EditRecord {
	protected static string $resource = ProductTypeResource::class;

	protected function getHeaderActions(): array {
		return [
			DeleteAction::make()
				->before(fn(ProductType $record) => ProductTypeResource::deleteAction($record))
		];
	}

	protected function afterSave(): void {
		PreviewHelper::deleteByProductType($this->record);
	}
}
