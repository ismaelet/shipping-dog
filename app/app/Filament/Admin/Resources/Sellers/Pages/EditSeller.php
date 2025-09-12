<?php

namespace App\Filament\Admin\Resources\Sellers\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\Sellers\SellerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeller extends EditRecord {
	protected static string $resource = SellerResource::class;

	protected function getHeaderActions(): array {
		return [
			DeleteAction::make(),
		];
	}
}
