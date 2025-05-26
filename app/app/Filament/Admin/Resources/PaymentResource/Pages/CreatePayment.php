<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord {

	protected static string $resource = PaymentResource::class;

	protected function afterCreate(): void {
		$this->record->seller->updatePaid();
		$this->record->seller->update([
			'request' => false
		]);
	}
}
