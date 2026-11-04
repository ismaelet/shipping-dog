<?php

namespace App\Filament\Admin\Resources\Shippings\Actions;

use App\Services\GlsService;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

/**
 * BulkTransmitParcels action.
 *
 * Add and confirm all the selected parcels.
 */
class BulkTransmitParcels
{
    /**
     * Create a new action.
     *
     * @return Action
     */
    public static function make(): BulkAction
    {
        return BulkAction::make('transmit')
            ->label('Trasmetti selezionate')
            ->requiresConfirmation()
            ->icon('icon-shipping')
            ->button()
            ->action(function (Collection $records): bool {
                // Add the parcels
                $request = function () use ($records) {
                    $glsService = new GlsService;

                    // Transmit the parcels
                    return $glsService->transmitParcels($records);
                };

                $response = $request();

                // Success
                if ($response['success']) {
                    Notification::make()
                        ->title('Trasmesse')
                        ->body($records->count().' spedizioni trasmesse correttamente')
                        ->success()
                        ->send();

                    return true;
                }

                // Error
                Notification::make()
                    ->title('Errore')
                    ->body($response['error'])
                    ->danger()
                    ->send();

                return false;
            });
    }
}
