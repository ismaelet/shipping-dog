<?php

namespace App\Filament\Admin\Resources\InventoryItems\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\InventoryItems\InventoryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryItem extends EditRecord {
	protected static string $resource = InventoryItemResource::class;

	protected function getHeaderActions(): array {
		return [
			DeleteAction::make(),
		];
	}
}
