<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WarningResource\Pages;
use App\Filament\Admin\Resources\WarningResource\RelationManagers;
use App\Models\Warning;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarningResource extends Resource {

	/**
	 * The model the resource corresponds to
	 */
	protected static ?string $model = Warning::class;

	/**
	 * The icon of the resource
	 */
	protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

	/**
	 * The label of a singular resource.
	 */
	protected static ?string $label = 'Avviso';

	/**
	 * The label of a plural resource.
	 */
	protected static ?string $pluralLabel = 'Avvisi';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 */
	protected static ?string $navigationGroup = 'Sistema';

	public static function form(Form $form): Form {
		return $form
			->schema([
				Forms\Components\Textarea::make('message')
					->label('Testo')
					->placeholder('Inserisci il testo dell\'avviso')
					->columnSpanFull()
					->required(),
				Forms\Components\DatePicker::make('start_date')
					->label('Data inizio')
					->default(fn() => now())
					->required(),
				Forms\Components\DatePicker::make('end_date')
					->label('Data fine')
					->default(fn() => now()->addDay())
					->required(),
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				Tables\Columns\TextColumn::make('id')
					->label('ID'),
				Tables\Columns\TextColumn::make('message')
					->limit(75)
					->label('Testo'),
				Tables\Columns\TextColumn::make('start_date')
					->label('Data inizio'),
				Tables\Columns\TextColumn::make('end_date')
					->label('Data fine'),
			])
			->filters([
				//
			])
			->actions([
				Tables\Actions\EditAction::make(),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			]);
	}

	public static function getRelations(): array {
		return [
			//
		];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListWarnings::route('/'),
			'create' => Pages\CreateWarning::route('/create'),
			'edit' => Pages\EditWarning::route('/{record}/edit'),
		];
	}
}
