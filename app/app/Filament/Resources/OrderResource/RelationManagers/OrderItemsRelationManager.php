<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class OrderItemsRelationManager extends RelationManager {

	protected static string $relationship = 'order_items';

	protected static ?string $title = 'Articoli';

	public function form(Form $form): Form {
		return $form
			->schema([
				Select::make('design_id')
					->label('Design')
					->relationship(
						name: 'design',
						titleAttribute: 'name'
					)
					->live()
					->preload()
					->required()
					->searchable()
					->afterStateUpdated(function (Set $set, Get $get) {
						$product = Product::where('design_id', $get('design_id'))
							->where('type_id', $get('type_id'))
							->first();
						$set('product_id', $product?->id);
						$set('seller_id', $product?->seller?->id);
					}),

				Select::make('type_id')
					->label('Categoria')
					->relationship(
						name: 'type',
						titleAttribute: 'name',
						modifyQueryUsing: fn (Builder $query) => $query->where('same_as_type_id', 0)
							->orWhere('gender_id', 1),
					)
					->live()
					->preload()
					->required()
					->searchable()
					->afterStateUpdated(function (Set $set, Get $get) {
						$product = Product::where('design_id', $get('design_id'))
							->where('type_id', $get('type_id'))
							->first();
						$set('product_id', $product?->id);
					}),

				Select::make('color_id')
					->label('Colore')
					->relationship('color', 'name')
					->searchable()
					->preload()
					->required(),

				Select::make('size_id')
					->label('Taglia')
					->options(fn (Get $get): Collection => ProductSize::whereHas('types', function ($query) use ($get) {
						$query->where('product_type_id', $get('type_id'));
					})->pluck('name', 'id'))
					->live()
					->required(),

				Select::make('position_id')
					->label('Stampa')
					->options(fn (Get $get): Collection => ProductPosition::whereHas('types', function ($query) use ($get) {
						$query->where('product_type_id', $get('type_id'));
					})->pluck('name', 'id'))
					->live()
					->required(),

				Hidden::make('product_id')
					->required()
					->live(),

				Hidden::make('seller_id')
					->required()
					->live(),
			]);
	}

	public function table(Table $table): Table {
		return $table
			->recordTitleAttribute('id')
			->columns([
				Tables\Columns\TextColumn::make('id')
					->numeric()
					->sortable(),
				Tables\Columns\TextColumn::make('product.name')
					->label('Design')
					->searchable()
					->wrap(true)
					->sortable(),
				Tables\Columns\TextColumn::make('type.name')
					->label('Categoria')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('color.name')
					->label('Colore')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('size.name')
					->label('Taglia')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('position.name')
					->label('Stampa')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('logo.name')
					->label('Logo')
					->placeholder('-')
					->searchable()
					->sortable(),
			])
			->filters([
				//
			])
			->headerActions([
				Tables\Actions\CreateAction::make(),
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
}
