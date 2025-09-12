<?php

namespace App\Filament\Admin\Resources\Shippings\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\Shippings\ShippingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShipping extends EditRecord {
	protected static string $resource = ShippingResource::class;

	protected function getHeaderActions(): array {
		return [
			DeleteAction::make(),
		];
	}
}
