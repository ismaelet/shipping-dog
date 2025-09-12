<?php

namespace App\Filament\Admin\Resources\Warnings;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\Warnings\Pages\ListWarnings;
use App\Filament\Admin\Resources\Warnings\Pages\CreateWarning;
use App\Filament\Admin\Resources\Warnings\Pages\EditWarning;
use App\Filament\Admin\Resources\WarningResource\Pages;
use App\Filament\Admin\Resources\WarningResource\RelationManagers;
use App\Models\Warning;
use Filament\Forms;
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
	protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-exclamation-triangle';

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
	protected static string | \UnitEnum | null $navigationGroup = 'Sistema';

	public static function form(Schema $schema): Schema {
		return $schema
			->components([
				Textarea::make('message')
					->label('Testo')
					->placeholder('Inserisci il testo dell\'avviso')
					->columnSpanFull()
					->required(),
				DatePicker::make('start_date')
					->label('Data inizio')
					->default(fn() => now())
					->required(),
				DatePicker::make('end_date')
					->label('Data fine')
					->default(fn() => now()->addDay())
					->required(),
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				TextColumn::make('id')
					->label('ID'),
				TextColumn::make('message')
					->limit(75)
					->label('Testo'),
				TextColumn::make('start_date')
					->label('Data inizio'),
				TextColumn::make('end_date')
					->label('Data fine'),
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

	public static function getRelations(): array {
		return [
			//
		];
	}

	public static function getPages(): array {
		return [
			'index' => ListWarnings::route('/'),
			'create' => CreateWarning::route('/create'),
			'edit' => EditWarning::route('/{record}/edit'),
		];
	}
}
