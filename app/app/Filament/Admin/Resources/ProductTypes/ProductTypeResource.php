<?php

namespace App\Filament\Admin\Resources\ProductTypes;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\ProductTypes\Pages\ListProductTypes;
use App\Filament\Admin\Resources\ProductTypes\Pages\CreateProductType;
use App\Filament\Admin\Resources\ProductTypes\Pages\EditProductType;
use App\Filament\Admin\Resources\ProductTypes\RelationManagers\ColorsRelationManager;
use App\Filament\Admin\Resources\ProductTypes\RelationManagers\MeasuresRelationManager;
use App\Filament\Admin\Resources\ProductTypes\RelationManagers\MockupSizesRelationManager;
use App\Filament\Admin\Resources\ProductTypes\RelationManagers\PositionsRelationManager;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductTypeResource extends Resource {

    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = ProductType::class;

    /**
     * The icon of the resource.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cube-transparent';

    /**
     * The label of a singular resource.
     */
    protected static ?string $label = 'Categoria';

    /**
     * The label of a plural resource.
     */
    protected static ?string $pluralLabel = 'Categorie';

    /**
     * The group in which the resource's navigation item will be displayed.
     */
    protected static string | \UnitEnum | null $navigationGroup = 'Struttura';

    /**
     * The sort order of the resource's navigation item.
     */
    protected static ?int $navigationSort = 0;


    /**
     * Get the query for the resource.
     */
    public static function getEloquentQuery(): Builder {
        return self::getModel()::query()->with('gender', 'colors', 'positions', 'mockup_sizes', 'mockup_sizes.position');
    }

    /**
     * Get the resource's form schema.
     *
     * @return \Filament\Schemas\Schema
     */
    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Impostazioni')
                            ->schema([
                                Fieldset::make('Impostazioni')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nome singolare')
                                            ->placeholder('Nome singolare')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('names')
                                            ->label('Nome plurale')
                                            ->placeholder('Nome plurale')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('tag')
                                            ->label('Tag')
                                            ->required()
                                            ->placeholder('Tag')
                                            ->maxLength(255),

                                        Select::make('positions')
                                            ->label('Posizioni di stampa')
                                            ->required()
                                            ->multiple()
                                            ->preload()
                                            ->relationship('positions', 'name'),

                                        Select::make('sizes')
                                            ->label('Taglie')
                                            ->required()
                                            ->multiple()
                                            ->preload()
                                            ->relationship('sizes', 'name'),

                                        Select::make('gender_id')
                                            ->label('Genere')
                                            ->required()
                                            ->preload()
                                            ->nullable(true)
                                            ->relationship('gender', 'name'),

                                        Select::make('variant_of')
                                            ->label('Variante di')
                                            ->relationship(
                                                name: 'main_type',
                                                titleAttribute: 'name',
                                                ignoreRecord: true
                                            )
                                            ->preload(),

                                        Checkbox::make('preorder')
                                            ->label('Consegna lunga')
                                            ->columnSpan(2),

                                        Fieldset::make('Prezzi')
                                            ->relationship('price')
                                            ->schema([
                                                TextInput::make('price')
                                                    ->label('Prezzo di default')
                                                    ->placeholder('Prezzo di default')
                                                    ->numeric()
                                                    ->prefix('€')
                                                    ->required(),

                                                TextInput::make('gain_w')
                                                    ->label('Guadagno bianchi')
                                                    ->placeholder('Guadagno di default')
                                                    ->numeric()
                                                    ->prefix('€')
                                                    ->required(),

                                                TextInput::make('gain_c')
                                                    ->label('Guadagno colorati')
                                                    ->placeholder('Guadagno di default')
                                                    ->numeric()
                                                    ->prefix('€')
                                                    ->required(),
                                            ])->columns(3)
                                            ->columnSpan('full'),

                                        Fieldset::make('Descrizioni')
                                            ->relationship('info')
                                            ->schema([
                                                Textarea::make('description')
                                                    ->label('Descrizione')
                                                    ->rows(5)
                                                    ->placeholder('Descrizione (una breve descrizione del prodotto)'),
                                                Textarea::make('details')
                                                    ->label('Dettagli tecnici')
                                                    ->rows(5)
                                                    ->placeholder('Dettagli tecnici (una lista di informazioni su composizione e vestibilità, andare a capo per ogni riga)'),
                                            ])->columnSpanFull(),
                                    ])->columns(3)
                            ]),
                        // Tabs\Tab::make('Dimensioni di stampa')
                        // 	->visible(fn($record) => (bool) $record)
                        // 	->schema(function ($record) {
                        // 		$mockups = $record->mockup_sizes->load('position');
                        // 		$inputs = [];

                        // 		$mockups->each(function ($mockup) use (&$inputs) {
                        // 			$inputs[] = Fieldset::make(function () use ($mockup) {
                        // 				TextInput::make('product-mockup_' . $mockup->tag . '-' . $mockup->position_id . '-pos_x_min')
                        // 					->label($mockup->position->name . ' - Posizione X minima')
                        // 					->placeholder('Posizione X minima')
                        // 					->numeric()
                        // 					->required();
                        // 			});
                        // 		});

                        // 		return $inputs;
                        // }),
                        Tab::make('Mockup')
                            ->visible(fn($record) => (bool) $record)
                            ->schema([
                                Repeater::make('colors')
                                    ->label('')
                                    ->relationship()
                                    ->schema(function ($record) {
                                        $type = $record;

                                        return [
                                            Grid::make()
                                                ->schema(
                                                    function ($record) use ($type) {
                                                        $uploaders = [];

                                                        if ($type) {
                                                            foreach ($type->positions->where('base_of', 0) as $position) {
                                                                $uploaders[] = FileUpload::make('product-mockup_' . $type->tag . '-' . $record->tag . '-' . $position->id)
                                                                    ->label($position->name . ' - Base')
                                                                    ->image()
                                                                    ->panelAspectRatio('1:1')
                                                                    ->disk('public')
                                                                    ->directory('product_types/mockups/' . $type->tag)
                                                                    ->getUploadedFileNameForStorageUsing(
                                                                        fn($record): string => $record->tag . '-' . $position->id . '.webp',
                                                                    );

                                                                $uploaders[] = FileUpload::make('product-mockup_' . $type->tag . '-' . $record->tag . '-' . $position->id . '-det')
                                                                    ->label($position->name . ' - Sovrapponi')
                                                                    ->image()
                                                                    ->panelAspectRatio('1:1')
                                                                    ->disk('public')
                                                                    ->directory('product_types/mockups/' . $type->tag)
                                                                    ->getUploadedFileNameForStorageUsing(
                                                                        fn($record): string => $record->tag . '-' . $position->id . '-det.webp',
                                                                    );
                                                            }
                                                        }

                                                        return $uploaders;
                                                    }
                                                )
                                        ];
                                    })
                                    ->grid([
                                        'default' => 1,
                                        'lg' => 2
                                    ])
                                    ->addable(false)
                                    ->deletable(false)
                                    ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                                    ->collapsed(),

                            ])

                    ])
                    ->contained(false)
            ])->columns(1);
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
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(fn($record) => self::deleteAction($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            ColorsRelationManager::class,
            MeasuresRelationManager::class,
            PositionsRelationManager::class,
            MockupSizesRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListProductTypes::route('/'),
            'create' => CreateProductType::route('/create'),
            'edit' => EditProductType::route('/{record}/edit'),
        ];
    }

    public static function deleteAction(ProductType $record) {
        Product::where('type_id', $record->id)->forceDelete();
    }
}
