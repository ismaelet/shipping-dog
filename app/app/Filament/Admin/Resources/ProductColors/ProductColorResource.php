<?php

namespace App\Filament\Admin\Resources\ProductColors;

use App\Filament\Admin\Resources\ProductColors\Pages\CreateProductColor;
use App\Filament\Admin\Resources\ProductColors\Pages\EditProductColor;
use App\Filament\Admin\Resources\ProductColors\Pages\ListProductColors;
use App\Models\Ecommerce\Product\ProductColor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductColorResource extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = ProductColor::class;

    /**
     * The icon of the resource.
     *
     * @var string
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    /**
     * The label of a singular resource.
     */
    protected static ?string $label = 'Colore';

    /**
     * The label of a plural resource.
     */
    protected static ?string $pluralLabel = 'Colori';

    /**
     * The group in which the resource's navigation item will be displayed.
     *
     * @var string
     */
    protected static string|\UnitEnum|null $navigationGroup = 'Struttura';

    /**
     * The sort order of the resource's navigation item.
     */
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Impostazioni')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->placeholder('Nome del colore')
                            ->required(),
                        TextInput::make('tag')
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
                        TextInput::make('reorder')
                            ->label('Ordine')
                            ->default(0)
                            ->placeholder('Ordine del colore'),
                        Checkbox::make('dark')
                            ->label('È scuro'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->numeric(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('dark')
                    ->label('È scuro')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductColors::route('/'),
            'create' => CreateProductColor::route('/create'),
            'edit' => EditProductColor::route('/{record}/edit'),
        ];
    }
}
