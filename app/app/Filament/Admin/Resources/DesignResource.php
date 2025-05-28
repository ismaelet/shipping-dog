<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DesignResource\Pages;
use App\Filament\Admin\Resources\DesignResource\RelationManagers;
use App\Helpers\FileHelper;
use App\Models\Ecommerce\Design;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DesignResource extends Resource {
	protected static ?string $model = Design::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon-pic';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Design';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Design';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Gestione Prodotti';

	public static function form(Form $form): Form {
		return $form
			->schema([
				//
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				TextColumn::make('id')
					->label('ID')
					->numeric()
					->sortable(),

				TextColumn::make('name')
					->label('Nome')
					->searchable()
					->sortable(),

				TextColumn::make('seller.name')
					->label('Shop')
					->searchable()
					->sortable(),

			])

			// The default sort column and direction
			->defaultSort('id', 'desc')

			->filters([
				//
			])
			->actions([
				Action::make('download')
					->label('Design')
					->icon('icon-download')
					->color('success')
					->action(
						fn(Design $record): BinaryFileResponse => FileHelper::download('designs_originals/' . $record->id . '.png')
					),
				Action::make('download-dark')
					->label('Scuro')
					->icon('icon-download')
					->color('info')
					->visible(fn(Design $record) => FileHelper::exists('designs_originals/' . $record->id . '_dark.png'))
					->action(
						fn(Design $record): BinaryFileResponse => FileHelper::download('designs_originals/' . $record->id . '_dark.png')
					),
				Action::make('download-vector')
					->label('Vettoriale')
					->icon('icon-download')
					->color('warning')
					->visible(fn(Design $record) =>
					FileHelper::exists('designs_vector/' . $record->id . '.pdf') ||
						FileHelper::exists('designs_vector/' . $record->id . '.ai'))
					->action(
						function (Design $record): BinaryFileResponse {
							$filePath = FileHelper::exists('designs_vector/' . $record->id . '.ai') ?
								'designs_vector/' . $record->id . '.ai' :
								'designs_vector/' . $record->id . '.pdf';

							return FileHelper::download($filePath);
						}
					),
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
			'index' => Pages\ListDesigns::route('/'),
			'create' => Pages\CreateDesign::route('/create'),
			'edit' => Pages\EditDesign::route('/{record}/edit'),
		];
	}
}
