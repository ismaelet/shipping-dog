<?php

namespace App\Filament\Admin\Resources\ProductTypes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MeasuresRelationManager extends RelationManager
{
    protected static string $relationship = 'measures';

    protected static ?string $title = 'Taglie';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('width')
                    ->label('Larghezza')
                    ->suffix('cm')
                    ->numeric()
                    ->required(),
                TextInput::make('height')
                    ->label('Altezza')
                    ->suffix('cm')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Taglia'),
                TextColumn::make('displayMeasure')
                    ->label('Dimensioni'),
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
