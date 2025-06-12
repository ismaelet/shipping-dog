<?php

namespace App\Filament\Admin\Resources\ShippingResource\Actions;

use App\Models\Ecommerce\Order;
use App\Services\GlsService;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

/**
 * AddParcel action.
 * 
 * Add a new GLS parcel to the weblabeling with an uncounfirmed status.
 */
class AddParcel {

	/**
	 * Create a new action.
	 * 
	 * @return Action
	 */
	public static function make(): Action {
		return Action::make('add-parcel')
			->label('Crea')
			->color('info')
			->requiresConfirmation()
			->icon('heroicon-o-plus')
			->action(function (Order $record): bool {
				// Add the parcel
				$request = function () use ($record) {
					$glsService = new GlsService();

					// Add the parcel
					$response = $glsService->addParcels($record);

					if (!$response) {
						return false;
					}

					return true;
				};

				$response = $request();

				// Success
				if ($response) {
					Notification::make()
						->title('Trasmesso')
						->body('Spedizione di ' . $record->full_name . ' creata correttamente')
						->success()
						->send();

					return true;
				}

				// Error
				Notification::make()
					->title('Errore')
					->body('Errore durante la creazione della spedizione')
					->danger()
					->send();

				return false;
			});
	}
}
