<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductPositionResource\Pages;
use App\Filament\Admin\Resources\ProductPositionResource\RelationManagers;
use App\Models\Ecommerce\Product\ProductPosition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductPositionResource extends Resource {
	protected static ?string $model = ProductPosition::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Posizione di stampa';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Posizioni';

	/**
	 * The sort order of the resource's navigation item.
	 * 
	 * @var int
	 */
	protected static ?int $navigationSort = 2;

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Struttura';

	public static function form(Form $form): Form {
		return $form
			->schema([
				Forms\Components\TextInput::make('name')
					->label('Nome')
					->required()
					->maxLength(255)
					->columnSpanFull(),
				Forms\Components\TextInput::make('tag')
					->label('Tag')
					->required()
					->maxLength(255)
					->columnSpanFull(),
				Forms\Components\Checkbox::make('editable')
					->label('È editabile')
					->default(true)
					->columnSpanFull(),
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				Tables\Columns\TextColumn::make('name')
					->label('Posizione')
					->searchable()
					->sortable(),
				Tables\Columns\IconColumn::make('editable')
					->label('È editabile')
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
			'index' => Pages\ListProductPositions::route('/'),
			'create' => Pages\CreateProductPosition::route('/create'),
			'edit' => Pages\EditProductPosition::route('/{record}/edit'),
		];
	}
}
