<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerResource\Pages;
use App\Models\Ecommerce\Seller;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SellerResource extends Resource {

	/**
	 * The model the resource corresponds to.
	 * 
	 * @var string
	 */
	protected static ?string $model = Seller::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon-shops';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Negozio';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Negozi';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Gestione Utenti';

	/**
	 * The sort order of the resource's navigation item.
	 * 
	 * @var int
	 */
	protected static ?int $navigationSort = 2;

	/**
	 * Get the display value of the resource navigation badge.
	 * 
	 * @return string|null
	 */
	public static function getNavigationBadge(): ?string {
		return static::getModel()::isAwaiting()->count();
	}

	/**
	 * Get the display color of the resource navigation badge.
	 * 
	 * @return string|null
	 */
	public static function getNavigationBadgeColor(): ?string {
		return static::getNavigationBadge() > 0 ? 'primary' : 'gray';
	}

	/**
	 * The form for creating or updating records.
	 * 
	 * @param \Filament\Forms\Form $form
	 * @return \Filament\Forms\Form
	 */
	public static function form(Form $form): Form {
		return $form
			->schema([
				//
			]);
	}

	/**
	 * Get the query for the resource.
	 * 
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function getEloquentQuery(): Builder {
		return self::$model::query()
			->select('stores.*')
			->selectRaw('earned - payed + other_transfers as to_be_paid');
	}

	/**
	 * The table for listing records.
	 * 
	 * @param \Filament\Tables\Table $table
	 * @return \Filament\Tables\Table
	 */
	public static function table(Table $table): Table {
		return $table

			// Columns
			->columns([
				// ID
				TextColumn::make('id')
					->searchable()
					->label('ID')
					->sortable(),

				// Nome
				TextColumn::make('name')
					->placeholder('-')
					->label('Nome')
					->searchable()
					->sortable(),

				// Earned
				TextColumn::make('earned')
					->label('Guadagnato')
					->sortable()
					->toggleable()
					->toggledHiddenByDefault()
					->money(currency: 'EUR'),

				// Paid
				TextColumn::make('payed')
					->label('Pagato')
					->sortable()
					->toggleable()
					->toggledHiddenByDefault()
					->money(currency: 'EUR'),

				// Other transactions
				TextColumn::make('other_transfers')
					->label('Altro')
					->sortable()
					->toggleable()
					->toggledHiddenByDefault()
					->money(currency: 'EUR'),

				// To be paid
				TextColumn::make('to_be_paid')
					->label('Da pagare')
					->sortable()
					->money(currency: 'EUR'),

				// Request
				IconColumn::make('request')
					->label('Richiesta di pagamento')
					->boolean()
					->toggleable()
					->toggledHiddenByDefault()
					->sortable(),

				// Status
				ToggleColumn::make('status')
					->label('Attivo')
					->sortable(),

			])

			// The default sort column and direction
			->defaultSort('id', 'desc')

			// Filters
			->filters([
				//
			])

			// Actions
			->actions([
				ActionGroup::make([
					Action::make('view')
						->label('Visualizza')
						->icon('icon-visibility')
						->url(fn (Seller $record): string => route('seller.show', $record))
						->openUrlInNewTab(true),
					Action::make('login')
						->icon('icon-login')
						->color('info')
						->action(function (Seller $record) {
							auth()->login($record->user);

							return to_route('user-dashboard');
						}),
					Action::make('remove-request')
						->label('Rimuovi richiesta')
						->icon('icon-close')
						->color('warning')
						->visible(fn (Seller $record) => $record->request)
						->action(fn (Seller $record) => $record->update(['request' => false])),
					DeleteAction::make()
						->icon('icon-delete'),
				])
			])

			// Bulk actions
			->bulkActions([]);
	}

	/**
	 * Get the pages available to the resource.
	 * 
	 * @return array
	 */
	public static function getPages(): array {
		return [
			'index' => Pages\ListSellers::route('/'),
			'edit' => Pages\EditSeller::route('/{record}/edit'),
		];
	}
}
