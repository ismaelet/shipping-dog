<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Ecommerce\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource {

	protected static ?string $model = Order::class;

	protected static ?string $navigationIcon = 'icon-orders';

	protected static ?string $label = 'Ordine';

	protected static ?string $pluralLabel = 'Ordini';

	protected static ?string $navigationGroup = 'Gestione Vendite';

	public static function form(Form $form): Form {
		return $form
			->schema([
				//
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				Tables\Columns\TextColumn::make('id')
					->numeric()
					->label('ID')
					->sortable(),
				Tables\Columns\TextColumn::make('status')
					->numeric()
					->label('Stato')
					->badge(function ($record) {
						return match ($record->status) {
							5 => 'confirmed-color',
							'processing' => 'info',
							'completed' => 'success',
							'cancelled' => 'danger',
							default => 'secondary',
						};
					})
					->sortable(),
			])
			->filters([
				//
			])
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
			'index' => Pages\ListOrders::route('/'),
			'create' => Pages\CreateOrder::route('/create'),
			'edit' => Pages\EditOrder::route('/{record}/edit'),
		];
	}
}
