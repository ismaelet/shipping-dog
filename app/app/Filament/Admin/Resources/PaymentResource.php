<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\Payment;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;

class PaymentResource extends Resource {

	/**
	 * The model the resource corresponds to.
	 * 
	 * @var string
	 */
	protected static ?string $model = Payment::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon-money';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Pagamento';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Pagamenti';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Utenti e Negozi';

	/**
	 * The sort order of the resource's navigation item.
	 * 
	 * @var int
	 */
	protected static ?int $navigationSort = 3;

	/**
	 * The form for creating records.
	 * 
	 * @param Form $form
	 * @return Form
	 */
	public static function form(Form $form): Form {
		return $form
			->schema([
				// Seller
				Select::make('seller_id')
					->label('Negozio')
					->placeholder('Seleziona un negozio')
					->relationship('seller', 'name')
					->searchable()
					->required(),

				// Amount
				TextInput::make('value')
					->label('Importo')
					->numeric()
					->placeholder('Inserisci l\'importo')
					->inputMode('decimal')
					->required()
					->suffix('€'),

				// Other transfer
				Checkbox::make('other')
					->label('Trasferenza esterna')
					->default(false),
			])->columns(1);
	}

	/**
	 * Get the badge value for the resource.
	 * 
	 * @return string|null
	 */
	public static function getNavigationBadge(): ?string {
		return Seller::where('request', true)->count();
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
	 * The table for listing records.
	 * 
	 * @param Table $table
	 * @return Table
	 */
	public static function table(Table $table): Table {
		return $table
			// Columns
			->columns([
				// ID
				Tables\Columns\TextColumn::make('id')
					->label('ID')
					->numeric()
					->searchable()
					->sortable(),

				// Seller
				Tables\Columns\TextColumn::make('seller.name')
					->label('Negozio')
					->placeholder('Negozio non trovato')
					->searchable()
					->sortable(),

				// Amount
				Tables\Columns\TextColumn::make('value')
					->label('Importo')
					->money(currency: 'EUR')
					->sortable(),

				// Other transfer
				Tables\Columns\IconColumn::make('other')
					->label('Trasferenza esterna')
					->boolean(),

				// Date
				Tables\Columns\TextColumn::make('created_at')
					->label('Data')
					->dateTime('d-m-Y')
					->sortable(),
			])

			// Sort
			->defaultSort('id', 'desc')

			// Actions
			->actions([
				Tables\Actions\EditAction::make(),
				Tables\Actions\DeleteAction::make(),
			]);
	}

	/**
	 * Get the pages available to the resource.
	 * 
	 * @return array
	 */
	public static function getPages(): array {
		return [
			'index' => Pages\ListPayments::route('/'),
			'create' => Pages\CreatePayment::route('/create'),
			'edit' => Pages\EditPayment::route('/{record}/edit'),
		];
	}
}
