<?php

namespace App\Filament\Admin\Resources\ProductColorResource\Pages;

use App\Filament\Admin\Resources\ProductColorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductColor extends EditRecord {
	protected static string $resource = ProductColorResource::class;

	protected function getHeaderActions(): array {
		return [
			Actions\DeleteAction::make(),
		];
	}
}
