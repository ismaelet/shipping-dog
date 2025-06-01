<?php

namespace App\Filament\Admin\Resources\ProductTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MockupSizesRelationManager extends RelationManager {
	protected static string $relationship = 'mockup_sizes';

	protected static ?string $title = 'Dimensioni mockup';

	public function form(Form $form): Form {
		return $form
			->schema([
				Select::make('position_id')
					->label('Posizione')
					->required()
					->relationship('position', 'name')
					->searchable()
					->preload()
					->columnSpanFull(),
				Forms\Components\TextInput::make('pos_x_min')
					->label('Inizio area di stampa X')
					->required()
					->numeric()
					->suffix('%')
					->default(20)
					->minValue(0)
					->maxValue(100),
				Forms\Components\TextInput::make('siz_x_max')
					->label('Dimensione area di stampa X')
					->required()
					->numeric()
					->suffix('%')
					->default(60)
					->minValue(0)
					->maxValue(100),
				Forms\Components\TextInput::make('pos_x_def')
					->label('Centro area di stampa X')
					->required()
					->numeric()
					->suffix('%')
					->default(50)
					->minValue(0)
					->maxValue(100),
				Forms\Components\TextInput::make('pos_y_min')
					->label('Inizio area di stampa Y')
					->required()
					->numeric()
					->suffix('%')
					->default(20)
					->minValue(0)
					->maxValue(100),
				Forms\Components\TextInput::make('siz_y_max')
					->label('Dimensione area di stampa Y')
					->required()
					->numeric()
					->suffix('%')
					->default(60)
					->minValue(0)
					->maxValue(100),
				Forms\Components\TextInput::make('pos_y_def')
					->label('Centro area di stampa Y')
					->required()
					->numeric()
					->suffix('%')
					->default(25)
					->minValue(0)
					->maxValue(100),
				Forms\Components\TextInput::make('siz_x_def')
					->label('Dimensione consigliata')
					->required()
					->numeric()
					->suffix('%')
					->default(90)
					->minValue(0)
					->maxValue(100),
			])->columns(3);
	}

	public function table(Table $table): Table {
		return $table
			->recordTitleAttribute('position.name')
			->columns([
				Tables\Columns\TextColumn::make('position.name')
					->label('Posizione')
					->sortable()
					->searchable(),
			])
			->filters([
				//
			])
			->headerActions([
				Tables\Actions\CreateAction::make(),
			])
			->actions([
				Tables\Actions\EditAction::make(),
				Tables\Actions\DeleteAction::make(),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			]);
	}
}
