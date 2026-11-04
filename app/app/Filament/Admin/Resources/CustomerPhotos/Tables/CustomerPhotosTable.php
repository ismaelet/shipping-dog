<?php

namespace App\Filament\Admin\Resources\CustomerPhotos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerPhotosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('server_image')
                    ->label('Immagine')
                    ->imageWidth(80)
                    ->imageHeight(100),
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('Nome')
                    ->sortable()
                    ->default('-')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Cognome')
                    ->sortable()
                    ->default('-')
                    ->searchable(),
                TextColumn::make('design.name')
                    ->label('Design')
                    ->sortable()
                    ->default('-')
                    ->searchable(),
                TextColumn::make('type.name')
                    ->label('Categoria')
                    ->sortable()
                    ->default('-')
                    ->toggleable()
                    ->toggledHiddenByDefault(true)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Caricata il')
                    ->dateTime('d/m/Y H:i')
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
}
