<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Orders\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords {
	protected static string $resource = OrderResource::class;

	protected function getHeaderActions(): array {
		return [
			CreateAction::make(),
		];
	}
}
