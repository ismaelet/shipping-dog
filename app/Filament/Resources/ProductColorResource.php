<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductColorResource\Pages;
use App\Filament\Resources\ProductColorResource\RelationManagers;
use App\Models\Ecommerce\Product\ProductColor;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductColorResource extends Resource {

	/**
	 * The model the resource corresponds to.
	 * 
	 * @var string
	 */
	protected static ?string $model = ProductColor::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon.s-palette';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Colore';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Colori';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Gestione Prodotti';

	/**
	 * The sort order of the resource's navigation item.
	 * 
	 * @var int
	 */
	protected static ?int $navigationSort = 2;

	public static function form(Form $form): Form {
		return $form
			->schema([
				Fieldset::make('Impostazioni')
					->schema([
						Forms\Components\TextInput::make('name')
							->label('Nome')
							->placeholder('Nome del colore')
							->required(),
						Forms\Components\TextInput::make('tag')
							->label('Tag')
							->placeholder('Tag del colore')
							->required(),
						ColorPicker::make('primary_color_hex')
							->label('Colore primario')
							->placeholder('Colore primario')
							->required(),
						ColorPicker::make('secondary_color_hex')
							->label('Colore secondario')
							->placeholder('Colore secondario (per colori doppi)'),
						Forms\Components\TextInput::make('reorder')
							->label('Ordine')
							->default(0)
							->placeholder('Ordine del colore'),
						Forms\Components\Checkbox::make('dark')
							->label('È scuro'),
					])
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				Tables\Columns\TextColumn::make('id')
					->searchable()
					->sortable()
					->numeric(),
				Tables\Columns\TextColumn::make('name')
					->label('Nome')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('tag')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('reorder')
					->label('Ordine')
					->sortable()
					->numeric(),
				Tables\Columns\IconColumn::make('dark')
					->label('È scuro')
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
			//
		];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListProductColors::route('/'),
			'create' => Pages\CreateProductColor::route('/create'),
			'edit' => Pages\EditProductColor::route('/{record}/edit'),
		];
	}
}
