<?php

namespace App\Filament\Admin\Resources\InventoryItems;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\InventoryItems\Pages\ListInventoryItems;
use App\Filament\Admin\Resources\InventoryItems\Pages\CreateInventoryItem;
use App\Filament\Admin\Resources\InventoryItems\Pages\EditInventoryItem;
use App\Filament\Admin\Resources\InventoryItemResource\Pages;
use App\Filament\Admin\Resources\InventoryItemResource\RelationManagers;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\InventoryItem;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductType;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class InventoryItemResource extends Resource {

    protected static ?string $model = InventoryItem::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard';

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
    protected static string | \UnitEnum | null $navigationGroup = 'Prodotti';

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Prodotto')
                    ->getSearchResultsUsing(
                        fn(string $search): array =>
                        Product::query()
                            ->where('design_id', null)
                            ->where(function (Builder $query) use ($search) {
                                $query->where('name', 'like', "%{$search}%");
                                $query->orWhere('id', $search);
                            })
                            ->limit(50)
                            ->pluck('name', 'id')
                            ->all()
                    )
                    ->getOptionLabelUsing(
                        fn($value): ?string =>
                        Product::query()
                            ->whereKey($value)
                            ->first()
                            ?->name
                    )
                    ->searchable()
                    ->visible(fn(?InventoryItem $record = null): bool => $record === null || $record->design_id === null)
                    ->preload()
                    ->helperText(fn($record) => !$record ? 'Qui è possibile cercare solo prodotti custom, quindi SENZA design associato. Scegliere il prodotto OPPURE design e tipo. Se selezionato il prodotto qui, lasciare vuoti design e tipo e viceversa.' : null)
                    ->columnSpanFull(),

                Select::make('design_id')
                    ->label('Design')
                    ->getSearchResultsUsing(
                        fn(string $search): array =>
                        Design::query()
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('id', $search)
                            ->limit(50)
                            ->pluck('name', 'id')
                            ->map(fn($name, $id) => "$id - $name")
                            ->all()
                    )
                    ->getOptionLabelUsing(
                        fn($value): ?string =>
                        Design::query()
                            ->whereKey($value)
                            ->first()
                            ?->name
                            ? "$value - " . Design::query()->whereKey($value)->value('name')
                            : null
                    )
                    ->visible(fn(?InventoryItem $record = null): bool => $record === null || $record->design_id)
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
                    ->visible(fn(?InventoryItem $record = null): bool => $record === null || $record->design_id)
                    ->live(),

                Select::make('color_id')
                    ->label('Colore')
                    ->relationship('color', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),

                Select::make('size_id')
                    ->label('Taglia')
                    ->options(fn(Get $get): Collection => ProductSize::when(
                        $get('type_id'),
                        fn($query) => $query->whereHas('types', function ($query) use ($get) {
                            $query->where('product_type_id', $get('type_id'));
                        }),
                        fn($query) => $query
                    )->pluck('name', 'id'))
                    ->live()
                    ->required(),

                Select::make('position_id')
                    ->label('Stampa')
                    ->options(fn(Get $get): Collection => ProductPosition::when(
                        $get('type_id'),
                        fn($query) => $query->whereHas('types', function ($query) use ($get) {
                            $query->where('product_type_id', $get('type_id'));
                        }),
                        fn($query) => $query
                    )->pluck('name', 'id'))
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

                TextColumn::make('product.name')
                    ->label('Prodotto')
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

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListInventoryItems::route('/'),
            'create' => CreateInventoryItem::route('/create'),
            'edit' => EditInventoryItem::route('/{record}/edit'),
        ];
    }
}
