<?php

namespace App\Filament\Admin\Resources\ShippingResource\Actions;

use App\Models\Ecommerce\Order;
use App\Services\GlsService;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

/**
 * TransmitParcel action.
 * 
 * Confirm a GLS parcel that alreadyt exists, with an uncounfirmed status.
 */
class TransmitParcel {

	/**
	 * Create a new action.
	 * 
	 * @return Action
	 */
	public static function make(): Action {
		return Action::make('confirm-parcel')
			->label('Trasmetti')
			->requiresConfirmation()
			->icon('heroicon-o-paper-airplane')
			->color('primary')
			->action(function (Order $record): bool {
				// Transmit the parcel
				$request = function () use ($record) {
					$glsService = new GlsService();

					// Transmit the parcel
					return $glsService->transmitParcels($record);
				};

				$response = $request();

				// Success
				if ($response['success']) {
					Notification::make()
						->title('Confermato')
						->body('Spedizione di ' . $record->full_name . ' trasmessa correttamente')
						->success()
						->send();

					return true;
				}

				// Error
				Notification::make()
					->title('Errore')
					->body('Errore durante la trasmissione della spedizione di ' . $record->full_name)
					->danger()
					->send();

				return false;
			});
	}
}
