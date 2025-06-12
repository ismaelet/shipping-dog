<?php

namespace App\Filament\Admin\Resources\ShippingResource\Actions;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

/**
 * RemoveLabeling action.
 * 
 * Set the status of an order to awaiting-shipping, skipping the labeling process.
 */
class RemoveLabeling {

	/**
	 * Create a new action.
	 * 
	 * @return Action
	 */
	public static function make(): Action {
		return Action::make('already-shipped')
			->label('Cancella')
			->requiresConfirmation()
			->icon('heroicon-o-trash')
			->color('danger')
			->action(function (Order $record): bool {
				$response = $record->update([
					'status' => EcommerceHelper::statusFromTag('awaiting-shipping'),
				]);

				// Success
				if ($response) {
					Notification::make()
						->title('Eliminata')
						->body('Spedizione eliminata correttamente')
						->success()
						->send();

					return true;
				}

				// Error
				Notification::make()
					->title('Errore')
					->body('Errore durante l\'eliminazione della spedizione')
					->danger()
					->send();

				return false;
			});
	}
}
