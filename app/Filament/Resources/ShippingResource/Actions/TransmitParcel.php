<?php

namespace App\Filament\Resources\ShippingResource\Actions;

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
		return Action::make('transmit-parcel')
			->label('Conferma la spedizione')
			->requiresConfirmation()
			->icon('icon-correct')
			->color('success')
			->action(function (Order $record): bool {
				// Transmit the parcel
				$request = function () use ($record) {
					$glsService = new GlsService();

					// Transmit the parcel
					$response = $glsService->transmitParcels($record);

					if (!$response) {
						return false;
					}

					return true;
				};

				$response = $request();

				// Success
				if ($response) {
					Notification::make()
						->title('Confermato')
						->body('Ordine di ' . $record->full_name . ' confermato correttamente')
						->success()
						->send();

					return true;
				}

				// Error
				Notification::make()
					->title('Errore')
					->body('Errore durante la conferma dell\'ordine')
					->danger()
					->send();

				return false;
			});
	}
}
