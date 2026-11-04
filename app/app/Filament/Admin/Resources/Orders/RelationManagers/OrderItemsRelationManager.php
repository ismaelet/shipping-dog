<?php

namespace App\Filament\Admin\Resources\Orders\RelationManagers;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'order_items';

    protected static ?string $title = 'Articoli';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Design')
                    ->searchable()
                    ->wrap(true)
                    ->sortable(),
                TextColumn::make('type.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('color.name')
                    ->label('Colore')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('size.name')
                    ->label('Taglia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position.name')
                    ->label('Stampa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('logo.name')
                    ->label('Logo')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
