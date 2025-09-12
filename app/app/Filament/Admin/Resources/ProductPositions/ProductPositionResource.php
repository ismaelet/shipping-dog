<?php

namespace App\Filament\Admin\Resources\ProductPositions;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\ProductPositions\Pages\ListProductPositions;
use App\Filament\Admin\Resources\ProductPositions\Pages\CreateProductPosition;
use App\Filament\Admin\Resources\ProductPositions\Pages\EditProductPosition;
use App\Filament\Admin\Resources\ProductPositionResource\Pages;
use App\Filament\Admin\Resources\ProductPositionResource\RelationManagers;
use App\Models\Ecommerce\Product\ProductPosition;
use Filament\Forms;
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
	protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-on-square';

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
	protected static string | \UnitEnum | null $navigationGroup = 'Struttura';

	public static function form(Schema $schema): Schema {
		return $schema
			->components([
				TextInput::make('name')
					->label('Nome')
					->required()
					->maxLength(255)
					->columnSpanFull(),
				TextInput::make('tag')
					->label('Tag')
					->required()
					->maxLength(255)
					->columnSpanFull(),
				Checkbox::make('editable')
					->label('È editabile')
					->default(true)
					->columnSpanFull(),
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				TextColumn::make('name')
					->label('Posizione')
					->searchable()
					->sortable(),
				IconColumn::make('editable')
					->label('È editabile')
					->boolean()
					->sortable(),
			])
			->filters([
				//
			])
			->recordActions([
				EditAction::make(),
			])
			->toolbarActions([
				BulkActionGroup::make([
					DeleteBulkAction::make(),
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
			'index' => ListProductPositions::route('/'),
			'create' => CreateProductPosition::route('/create'),
			'edit' => EditProductPosition::route('/{record}/edit'),
		];
	}
}
