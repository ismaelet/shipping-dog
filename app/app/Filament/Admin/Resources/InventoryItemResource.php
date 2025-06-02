<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InventoryItemResource\Pages;
use App\Filament\Admin\Resources\InventoryItemResource\RelationManagers;
use App\Models\Ecommerce\InventoryItem;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class InventoryItemResource extends Resource {

	protected static ?string $model = InventoryItem::class;

	protected static ?string $navigationIcon = 'heroicon-o-clipboard';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Articolo in inventario';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Inventario';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Prodotti';

	public static function form(Form $form): Form {
		return $form
			->schema([
				Select::make('design_id')
					->label('Design')
					->relationship('design', 'id')
					->required()
					->searchable()
					->preload(),

				Select::make('type_id')
					->label('Tipo')
					->relationship(
						name: 'type',
						titleAttribute: 'name',
						modifyQueryUsing: fn(Builder $query) => $query->where('same_as_type_id', 0)
							->orWhere('gender_id', 1),
					)
					->live()
					->required(),

				Select::make('color_id')
					->label('Colore')
					->relationship('color', 'name')
					->searchable()
					->preload()
					->required(),

				Select::make('size_id')
					->label('Taglia')
					->options(fn(Get $get): Collection => ProductSize::whereHas('types', function ($query) use ($get) {
						$query->where('product_type_id', $get('type_id'));
					})->pluck('name', 'id'))
					->live()
					->required(),

				Select::make('position_id')
					->label('Stampa')
					->options(fn(Get $get): Collection => ProductPosition::whereHas('types', function ($query) use ($get) {
						$query->where('product_type_id', $get('type_id'));
					})->pluck('name', 'id'))
					->live()
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

				TextColumn::make('design.name')
					->label('Design')
					->searchable()
					->sortable(),

				TextColumn::make('type.name')
					->label('Tipo')
					->searchable()
					->sortable(),

				TextColumn::make('color.name')
					->label('Colore')
					->searchable()
					->sortable(),

				TextColumn::make('size.name')
					->label('Taglia')
					->sortable(),

				TextColumn::make('position.name')
					->label('Stampa')
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
			'index' => Pages\ListInventoryItems::route('/'),
			'create' => Pages\CreateInventoryItem::route('/create'),
			'edit' => Pages\EditInventoryItem::route('/{record}/edit'),
		];
	}
}
