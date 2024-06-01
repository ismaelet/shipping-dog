<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerResource\Pages;
use App\Models\Ecommerce\Seller;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Http\RedirectResponse;

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
				Action::make('view')
					->label('Visualizza')
					->icon('icon-visibility')
					->url(fn (Seller $record): string => route('seller.show', $record))
					->openUrlInNewTab(true),
				DeleteAction::make()
					->icon('icon-delete'),
				Action::make('login')
					->icon('icon-login')
					->color('info')
					->visible(fn () => is_superadmin())
					->action(function (Seller $record): RedirectResponse {
						auth()->login($record->user);

						return to_route('user-dashboard');
					}),
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
