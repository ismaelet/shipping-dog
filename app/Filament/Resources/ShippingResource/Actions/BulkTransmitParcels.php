<?php

namespace App\Filament\Resources\ShippingResource\Actions;

use App\Services\GlsService;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

/**
 * BulkTransmitParcels action.
 * 
 * Add and confirm all the selected parcels.
 */
class BulkTransmitParcels {

	/**
	 * Create a new action.
	 * 
	 * @return Action
	 */
	public static function make(): BulkAction {
		return BulkAction::make('transmit')
			->label('Trasmetti selezionate')
			->requiresConfirmation()
			->icon('icon-shipping')
			->button()
			->action(function (Collection $records): bool {
				// Add the parcels
				$request = function () use ($records) {
					$glsService = new GlsService();

					// Add the parcels
					$response = $glsService->addParcels($records);

					if (!$response) {
						return false;
					}

					// Transmit the parcels
					$response = $glsService->transmitParcels($records);

					if (!$response) {
						return false;
					}

					return true;
				};

				$response = $request();

				// Success
				if ($response) {
					Notification::make()
						->title('Trasmesse')
						->body('Tutte le spedizioni selezionate sono state trasmesse correttamente')
						->success()
						->send();

					return true;
				}

				// Error
				Notification::make()
					->title('Errore')
					->body('Errore durante la trasmissione degli ordini')
					->danger()
					->send();

				return false;
			});
	}
}
