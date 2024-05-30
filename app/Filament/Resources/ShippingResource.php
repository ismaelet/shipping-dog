<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingResource\Actions\AddParcel;
use App\Filament\Resources\ShippingResource\Actions\BulkTransmitParcels;
use App\Filament\Resources\ShippingResource\Actions\RemoveLabeling;
use App\Filament\Resources\ShippingResource\Actions\TransmitParcel;
use App\Filament\Resources\ShippingResource\Pages\ListShippings;
use App\Models\Ecommerce\Order;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ShippingResource extends Resource {

	/**
	 * The model the resource corresponds to.
	 * 
	 * @var string
	 */
	protected static ?string $model = Order::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Gestione Vendite';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Spedizione';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Spedizioni';

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon-shipping';


	/**
	 * Get the query for the resource.
	 */
	public static function getEloquentQuery(): Builder {
		return self::getModel()::hasNoLabel();
	}

	/**
	 * Get the badge value for the resource.
	 * 
	 * @return string|null
	 */
	public static function getNavigationBadge(): ?string {
		return static::getEloquentQuery()->count();
	}

	/**
	 * Get the badge color for the resource.
	 * 
	 * @return string|null
	 */
	public static function getNavigationBadgeColor(): ?string {
		return static::getNavigationBadge() > 0 ? 'primary' : 'neutral';
	}

	/**
	 * Determine whether the resource can be created.
	 * 
	 * @return bool
	 */
	public static function canCreate(): bool {
		return false;
	}

	/**
	 * Get the table schema for the resource.
	 * 
	 * @param \Filament\Tables\Table $table
	 * @return \Filament\Tables\Table
	 */
	public static function table(Table $table): Table {
		return $table

			// The columns displayed in the table
			->columns([
				TextColumn::make('id')
					->searchable()
					->label('ID')
					->sortable(),
				TextColumn::make('full_name')
					->label('Destinatario')
					->placeholder('-')
					->searchable()
					->sortable(),
				TextColumn::make('created_at')
					->dateTime('d-m-Y')
					->label('Ricevuto il')
					->sortable(),
			])

			// The actions that can be performed on the records
			->actions([
				AddParcel::make(),
				TransmitParcel::make(),
				RemoveLabeling::make(),
			])

			// The bulk actions that can be performed on the selected records
			->bulkActions([BulkTransmitParcels::make()]);
	}

	/**
	 * Get the pages for the resource.
	 * 
	 * @return array
	 */
	public static function getPages(): array {
		return [
			'index' => ListShippings::route('/'),
		];
	}
}
