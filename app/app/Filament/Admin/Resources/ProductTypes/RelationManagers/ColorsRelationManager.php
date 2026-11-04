<?php

namespace App\Filament\Admin\Resources\ProductTypes\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ColorsRelationManager extends RelationManager
{
    protected static string $relationship = 'colors';

    protected static ?string $title = 'Colori';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Colore')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('dark')
                    ->label('È scuro')
                    ->boolean()
                    ->sortable(),
            ])
            ->paginated(false)
            ->headerActions([
                AttachAction::make()
                    ->attachAnother(false)
                    ->multiple()
                    ->recordSelect(
                        fn (Select $select) => $select
                            ->placeholder('Seleziona i colori da collegare'),
                    )
                    ->after(function (array $data): void {
                        $typeId = $this->getOwnerRecord()->getKey();
                        $colorIds = (array) $data['recordId'];

                        DB::transaction(function () use ($typeId, $colorIds): void {
                            foreach ($colorIds as $colorId) {
                                DB::table('product_product_color')->insertUsing(
                                    [
                                        'product_id',
                                        'product_color_id',
                                    ],
                                    DB::table('products')
                                        ->select([
                                            'products.id',
                                            DB::raw((int) $colorId),
                                        ])
                                        ->where('products.type_id', $typeId)
                                        ->whereNotExists(function ($query) use ($colorId) {
                                            $query
                                                ->selectRaw('1')
                                                ->from('product_product_color')
                                                ->whereColumn(
                                                    'product_product_color.product_id',
                                                    'products.id'
                                                )
                                                ->where(
                                                    'product_product_color.product_color_id',
                                                    $colorId
                                                );
                                        })
                                );
                            }
                        });
                    }),
            ])
            ->recordActions([
                DetachAction::make()
                    ->after(function ($record): void {
                        $typeId = $this->getOwnerRecord()->getKey();

                        DB::table('product_product_color')
                            ->where('product_color_id', $record->getKey())
                            ->whereIn(
                                'product_id',
                                DB::table('products')
                                    ->select('id')
                                    ->where('type_id', $typeId)
                            )
                            ->delete();
                    }),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->after(function ($records): void {
                        $typeId = $this->getOwnerRecord()->getKey();
                        $colorIds = $records->modelKeys();

                        DB::table('product_product_color')
                            ->whereIn('product_color_id', $colorIds)
                            ->whereIn(
                                'product_id',
                                DB::table('products')
                                    ->select('id')
                                    ->where('type_id', $typeId)
                            )
                            ->delete();
                    }),
            ])
            ->inverseRelationship('types');
    }
}
