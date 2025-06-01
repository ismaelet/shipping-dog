<?php

namespace App\Filament\Admin\Resources\ProductTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PositionsRelationManager extends RelationManager {

	protected static string $relationship = 'all_positions';

	protected static ?string $title = 'Posizioni di stampa';

	public function form(Form $form): Form {
		return $form
			->schema([
				Checkbox::make('printable')
					->label('Stampabile')
					->default(true)
					->columnSpanFull(),
			]);
	}

	public function table(Table $table): Table {
		return $table
			->recordTitleAttribute('name')
			->columns([
				Tables\Columns\TextColumn::make('name')
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
				Tables\Actions\AttachAction::make()
					->attachAnother(false)
					->multiple()
					->recordSelect(
						fn(Select $select) => $select->placeholder('Aggiungi le posizioni di stampa'),
					),
			])
			->actions([
				Tables\Actions\EditAction::make(),
				Tables\Actions\DeleteAction::make(),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			])
			->inverseRelationship('types');
	}
}
