<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTypeResource\Pages;
use App\Filament\Resources\ProductTypeResource\RelationManagers;
use App\Filament\Resources\ProductTypeResource\RelationManagers\ColorsRelationManager;
use App\Models\Ecommerce\Product\ProductGender;
use App\Models\Ecommerce\Product\ProductType;
use BladeUI\Icons\Components\Icon;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductTypeResource extends Resource {

	/**
	 * The model the resource corresponds to.
	 * 
	 * @var string
	 */
	protected static ?string $model = ProductType::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon-categories';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Categoria';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Categorie';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Gestione Prodotti';

	/**
	 * Get the query for the resource.
	 * 
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function getEloquentQuery(): Builder {
		return self::getModel()::query()->with('gender', 'colors');
	}

	public static function form(Form $form): Form {
		return $form
			->schema([
				Fieldset::make('Impostazioni')
					->schema([
						TextInput::make('name')
							->label('Nome singolare')
							->placeholder('Nome singolare')
							->maxLength(255),
						TextInput::make('names')
							->label('Nome plurale')
							->placeholder('Nome plurale')
							->maxLength(255),
						Select::make('gender_id')
							->label('Genere')
							->placeholder('Seleziona un genere')
							->relationship('gender', 'name')
					])->grow(true),
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				// ID
				TextColumn::make('id')
					->label('ID')
					->searchable()
					->numeric()
					->sortable(),

				// First name
				TextColumn::make('name')
					->label('Nome')
					->searchable()
					->sortable(),

				// Genere
				TextColumn::make('gender.name')
					->label('Anteprima')
					->searchable()
					->sortable(),

				// Colors
				TextColumn::make('colors.name')
					->wrap()
					->label('Colori')
					->searchable()
					->sortable(),

				// Unisex
				IconColumn::make('unisex')
					->label('Unisex')
					->boolean()
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
			ColorsRelationManager::class,
		];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListProductTypes::route('/'),
			'create' => Pages\CreateProductType::route('/create'),
			'edit' => Pages\EditProductType::route('/{record}/edit'),
		];
	}
}
