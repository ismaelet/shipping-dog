<?php

namespace App\Filament\Admin\Resources\ProductTypes\Pages;

use App\Filament\Admin\Resources\ProductTypes\ProductTypeResource;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class CreateProductType extends CreateRecord
{
    protected static string $resource = ProductTypeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $sortOrder = ProductTypeResource::getModel()::max('sort') + 1;
        $data['sort'] = $sortOrder;

        return static::getModel()::create($data);
    }

    protected function afterCreate(): void
    {
        $type = $this->record;
        $colorIds = $type->colors->pluck('id');
        $colorId = $colorIds->first();
        $colorTags = $type->colors->pluck('tag');
        $availableColorSlugs = implode(',', $colorTags->toArray());
        $positions = implode(',', $type->positions->pluck('id')->toArray());

        $designs = Design::all();

        $designs->each(function (Design $design) use ($type, $colorId, $availableColorSlugs, $positions) {
            Product::create([
                'name' => $design->name,
                'design_id' => $design->id,
                'user_id' => $design->user_id,
                'type_id' => $type->id,
                'color_id' => $colorId,
                'colors' => $availableColorSlugs,
                'positions' => $positions,
            ]);
        });
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Impostazioni')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome singolare')
                            ->required()
                            ->placeholder('Nome singolare')
                            ->maxLength(255),
                        TextInput::make('names')
                            ->label('Nome plurale')
                            ->required()
                            ->placeholder('Nome plurale')
                            ->maxLength(255),
                        TextInput::make('tag')
                            ->label('Tag')
                            ->required()
                            ->placeholder('Tag')
                            ->maxLength(255),
                        Select::make('gender_id')
                            ->label('Genere')
                            ->placeholder('Seleziona un genere')
                            ->relationship('gender', 'name'),
                        Select::make('colors')
                            ->label('Colori')
                            ->required()
                            ->multiple()
                            ->preload()
                            ->relationship('colors', 'name'),
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

                    ]),
            ]);
    }
}
