<?php

namespace App\Filament\Admin\Resources\ProductTypes\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Services\Files\ProductPreviewProcessor;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MockupSizesRelationManager extends RelationManager {
    protected static string $relationship = 'mockup_sizes';

    protected static ?string $title = 'Dimensioni mockup';

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('position_id')
                        ->label('Posizione')
                        ->required()
                        ->relationship('position', 'name')
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                    TextInput::make('pos_x_min')
                        ->label('Inizio area di stampa X')
                        ->required()
                        ->numeric()
                        ->suffix('%')
                        ->default(20)
                        ->minValue(0)
                        ->maxValue(100),
                    TextInput::make('siz_x_max')
                        ->label('Dimensione area di stampa X')
                        ->required()
                        ->numeric()
                        ->suffix('%')
                        ->default(60)
                        ->minValue(0)
                        ->maxValue(100),
                    TextInput::make('pos_x_def')
                        ->label('Centro area di stampa X')
                        ->required()
                        ->numeric()
                        ->suffix('%')
                        ->default(50)
                        ->minValue(0)
                        ->maxValue(100),
                    TextInput::make('pos_y_min')
                        ->label('Inizio area di stampa Y')
                        ->required()
                        ->numeric()
                        ->suffix('%')
                        ->default(20)
                        ->minValue(0)
                        ->maxValue(100),
                    TextInput::make('siz_y_max')
                        ->label('Dimensione area di stampa Y')
                        ->required()
                        ->numeric()
                        ->suffix('%')
                        ->default(60)
                        ->minValue(0)
                        ->maxValue(100),
                    TextInput::make('pos_y_def')
                        ->label('Centro area di stampa Y')
                        ->required()
                        ->numeric()
                        ->suffix('%')
                        ->default(25)
                        ->minValue(0)
                        ->maxValue(100),
                    TextInput::make('siz_x_def')
                        ->label('Dimensione consigliata')
                        ->required()
                        ->numeric()
                        ->suffix('%')
                        ->default(90)
                        ->minValue(0)
                        ->maxValue(100),
                ])->columns(3),
                ViewField::make('rating')
                    ->view('filament.forms.components.mockup-previewer')->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->recordTitleAttribute('position.name')
            ->columns([
                TextColumn::make('position.name')
                    ->label('Posizione')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make()->after(function ($record) {
                    app(ProductPreviewProcessor::class)->deleteByProductType($record->type);
                }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
