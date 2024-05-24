<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingResource\Pages;
use App\Models\Ecommerce\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
				//
			])
			->filters([])
			->actions([
				Tables\Actions\EditAction::make(),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			]);
	}

	public static function getRelations(): array {
		return [
			//
		];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListShippings::route('/'),
			'create' => Pages\CreateShipping::route('/create'),
			'edit' => Pages\EditShipping::route('/{record}/edit'),
		];
	}
}
