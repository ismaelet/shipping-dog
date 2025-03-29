<?php

namespace App\Filament\Resources\ProductTypeResource\Pages;

use App\Filament\Resources\ProductTypeResource;
use App\Helpers\FileHelper;
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
		$files = glob(public_storage('products/' . $this->record->tag . '/*'));

		array_map(
			function ($file) {
				$file = str_replace(public_storage(), '', $file);
				FileHelper::delete($file);
			},
			$files
		);
	}
}
