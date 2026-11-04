<?php

namespace App\Filament\Admin\Resources\ProductTypes\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'all_positions';

    protected static ?string $title = 'Posizioni di stampa';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Checkbox::make('printable')
                    ->label('Stampabile')
                    ->default(true)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Posizione'),
                IconColumn::make('printable')
                    ->label('È stampabile')
                    ->boolean()
                    ->sortable(),
            ])
            ->paginated(false)
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->attachAnother(false)
                    ->multiple()
                    ->recordSelect(
                        fn (Select $select) => $select->placeholder('Aggiungi le posizioni di stampa'),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->inverseRelationship('types');
    }
}
