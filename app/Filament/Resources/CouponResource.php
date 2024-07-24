<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Ecommerce\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource {

	protected static ?string $model = Coupon::class;

	protected static ?string $navigationIcon = 'icon-coupons';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Codice sconto';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Codici sconto';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Gestione Prodotti';

	public static function form(Form $form): Form {
		return $form
			->schema([
				Forms\Components\TextInput::make('code')
					->label('Codice')
					->columnSpanFull()
					->default(uniqid())
					->required(),

				Forms\Components\TextInput::make('mod_us')
					->label('Percentuale nostra')
					->default(0)
					->suffix('%')
					->required(),

				Forms\Components\TextInput::make('mod_them')
					->label('Percentuale negozio')
					->default(0)
					->suffix('%')
					->required(),

				Forms\Components\Checkbox::make('permanent')
					->label('Riusabile'),

				Forms\Components\Checkbox::make('shipping')
					->label('Spedizione gratis'),

				Forms\Components\DatePicker::make('time_start')
					->label('Attivo dal')
					->default(now())
					->required(),

				Forms\Components\DatePicker::make('time_end')
					->label('Fino al')
					->default(now()->addYear())
					->required(),
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				TextColumn::make('id')
					->label('ID')
					->numeric()
					->sortable(),

				TextColumn::make('code')
					->label('Codice')
					->searchable()
					->sortable(),

				TextColumn::make('mod_us')
					->label('Percentuale nostra')
					->suffix('%')
					->sortable(),

				TextColumn::make('mod_them')
					->label('Percentuale negozio')
					->suffix('%')
					->sortable(),

				IconColumn::make('permanent')
					->label('Riusabile')
					->boolean(),

				IconColumn::make('shipping')
					->label('Spedizione gratis')
					->boolean()
					->toggleable()
					->toggledHiddenByDefault()
					->sortable(),

				TextColumn::make('time_start')
					->label('Attivo dal')
					->dateTime('d-m-Y')
					->sortable(),

				TextColumn::make('time_end')
					->label('Fino al')
					->dateTime('d-m-Y')
					->sortable(),
			])

			// The default sort column and direction
			->defaultSort('id', 'desc')

			->filters([
				//
			])
			->actions([
				Tables\Actions\EditAction::make(),
				Tables\Actions\DeleteAction::make(),
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
			'index' => Pages\ListCoupons::route('/'),
			'create' => Pages\CreateCoupon::route('/create'),
			'edit' => Pages\EditCoupon::route('/{record}/edit'),
		];
	}
}
