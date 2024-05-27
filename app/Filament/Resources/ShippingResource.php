<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingResource\Pages;
use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Services\GlsService;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ShippingResource extends Resource {

	protected static ?string $model = Order::class;

	protected static ?string $navigationGroup = 'Gestione Vendite';

	protected static ?string $label = 'Spedizione';

	protected static ?string $pluralLabel = 'Spedizioni';

	protected static ?string $navigationIcon = 'icon-shipping';

	public static function getNavigationBadge(): ?string {
		return static::getModel()::hasNoLabel()->count();
	}

	public static function getNavigationBadgeColor(): ?string {
		return static::getModel()::count() > 0 ? 'primary' : 'neutral';
	}

	public static function canCreate(): bool {
		return false;
	}

	public static function form(Form $form): Form {
		return $form
			->schema([
				//
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->query(Order::hasNoLabel())
			->columns([
				Tables\Columns\TextColumn::make('id')
					->searchable()
					->label('ID')
					->sortable(),
				Tables\Columns\TextColumn::make('full_name')
					->label('Destinatario')
					->placeholder('-')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('created_at')
					->dateTime('d-m-Y')
					->label('Ricevuto il')
					->sortable(),
			])
			->filters([])
			->actions([
				Action::make('add-parcel')
					->label('Aggiungi la spedizione')
					->requiresConfirmation()
					->icon('icon-shipping')
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
								->body('Ordine di ' . $record->full_name . ' trasmesso correttamente')
								->success()
								->send();

							return true;
						}

						// Error
						Notification::make()
							->title('Errore')
							->body('Errore durante la trasmissione dell\'ordine')
							->danger()
							->send();

						return false;
					}),
				Action::make('transmit-parcel')
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
					}),
				Action::make('already-shipped')
					->label('Cancella la spedizione')
					->requiresConfirmation()
					->icon('icon-delete')
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
					})
			])
			->headerActions([])
			->bulkActions([
				BulkAction::make('transmit')
					->label('Trasmetti tutte')
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
								->body('Tutte le spedizioni sono state trasmesse correttamente')
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
					})
			]);
	}

	public static function getRelations(): array {
		return [];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListShippings::route('/'),
		];
	}
}
