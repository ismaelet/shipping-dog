<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductTypeResource\Pages;
use App\Filament\Resources\ProductTypeResource\RelationManagers;
use App\Filament\Resources\ProductTypeResource\RelationManagers\ColorsRelationManager;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductGender;
use App\Models\Ecommerce\Product\ProductType;
use BladeUI\Icons\Components\Icon;
use ColorRepeater;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use MockupUploader;

class ProductTypeResource extends Resource {

	/**
	 * The model the resource corresponds to.
	 * 
	 * @var string
	 */
	protected static ?string $model = ProductType::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon-categories';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Categoria';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Categorie';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Gestione Prodotti';

	/**
	 * Get the query for the resource.
	 * 
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function getEloquentQuery(): Builder {
		return self::getModel()::query()->with('gender', 'colors', 'positions', 'mockup_sizes', 'mockup_sizes.position');
	}

	public static function form(Form $form): Form {
		return $form
			->schema([
				Tabs::make('Tabs')
					->tabs([
						Tabs\Tab::make('Impostazioni')
							->schema([
								Fieldset::make('Impostazioni')
									->schema([
										TextInput::make('name')
											->label('Nome singolare')
											->placeholder('Nome singolare')
											->required()
											->maxLength(255),
										TextInput::make('names')
											->label('Nome plurale')
											->placeholder('Nome plurale')
											->required()
											->maxLength(255),
										// Select::make('gender_id')
										// 	->label('Genere')
										// 	->placeholder('Seleziona un genere')
										// 	->preload()
										// 	->nullable()
										// 	->relationship('gender', 'name'),
										TextInput::make('tag')
											->label('Tag')
											->required()
											->placeholder('Tag')
											->maxLength(255),
										Select::make('positions')
											->label('Posizioni di stampa')
											->required()
											->multiple()
											->preload()
											->relationship('positions', 'name'),
										Select::make('sizes')
											->label('Taglie')
											->required()
											->multiple()
											->preload()
											->relationship('sizes', 'name'),
										Checkbox::make('preorder')
											->label('Consegna lunga')
											->columnSpan(2),
										Fieldset::make('Prezzi')
											->relationship('price')
											->schema([
												TextInput::make('price')
													->label('Prezzo di default')
													->placeholder('Prezzo di default')
													->numeric()
													->prefix('€')
													->required(),

												TextInput::make('gain_w')
													->label('Guadagno bianchi')
													->placeholder('Guadagno di default')
													->numeric()
													->prefix('€')
													->required(),

												TextInput::make('gain_c')
													->label('Guadagno colorati')
													->placeholder('Guadagno di default')
													->numeric()
													->prefix('€')
													->required(),
											])->columns(3)
											->columnSpan('full'),
										Fieldset::make('Descrizioni')
											->relationship('info')
											->schema([
												Textarea::make('description')
													->label('Descrizione')
													->rows(5)
													->placeholder('Prezzo di default'),
												Textarea::make('details')
													->label('Dettagli tecnici')
													->rows(5)
													->placeholder('Prezzo di default'),
											])

									])->columns(3)
							]),
						// Tabs\Tab::make('Dimensioni di stampa')
						// 	->visible(fn($record) => (bool) $record)
						// 	->schema(function ($record) {
						// 		$mockups = $record->mockup_sizes->load('position');
						// 		$inputs = [];

						// 		$mockups->each(function ($mockup) use (&$inputs) {
						// 			$inputs[] = Fieldset::make(function () use ($mockup) {
						// 				TextInput::make('product-mockup_' . $mockup->tag . '-' . $mockup->position_id . '-pos_x_min')
						// 					->label($mockup->position->name . ' - Posizione X minima')
						// 					->placeholder('Posizione X minima')
						// 					->numeric()
						// 					->required();
						// 			});
						// 		});

						// 		return $inputs;
						// }),
						Tabs\Tab::make('Mockup')
							->visible(fn($record) => (bool) $record)
							->schema([
								Repeater::make('colors')
									->label('')
									->relationship()
									->schema(function ($record) {
										$type = $record;

										return [
											Grid::make()
												->schema(
													function ($record) use ($type) {
														$uploaders = [];

														if ($type) {
															foreach ($type->positions->where('base_of', 0) as $position) {
																$uploaders[] = FileUpload::make('product-mockup_' . $type->tag . '-' . $record->tag . '-' . $position->id)
																	->label($position->name . ' - Base')
																	->image()
																	->imageResizeMode('cover')
																	->imageCropAspectRatio('1:1')
																	->imageResizeTargetWidth('1024')
																	->imageResizeTargetHeight('1024')
																	->panelAspectRatio('1:1')
																	->disk('public')
																	->directory('product_types/mockups/' . $type->tag)
																	->getUploadedFileNameForStorageUsing(
																		fn($record): string => $record->tag . '-' . $position->id . '.webp',
																	);

																$uploaders[] = FileUpload::make('product-mockup_' . $type->tag . '-' . $record->tag . '-' . $position->id . '-det')
																	->label($position->name . ' - Sovrapponi')
																	->image()
																	->imageResizeMode('cover')
																	->imageCropAspectRatio('1:1')
																	->imageResizeTargetWidth('1024')
																	->imageResizeTargetHeight('1024')
																	->panelAspectRatio('1:1')
																	->disk('public')
																	->directory('product_types/mockups/' . $type->tag)
																	->getUploadedFileNameForStorageUsing(
																		fn($record): string => $record->tag . '-' . $position->id . '-det.webp',
																	);
															}
														}

														return $uploaders;
													}
												)
										];
									})
									->grid([
										'default' => 1,
										'lg' => 2
									])
									->addable(false)
									->deletable(false)
									->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
									->collapsed(),

							])

					])
					->contained(false)
			])->columns(1);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				// ID
				TextColumn::make('id')
					->label('ID')
					->searchable()
					->numeric()
					->sortable(),

				// First name
				TextColumn::make('name')
					->label('Nome')
					->searchable()
					->sortable(),

				// Genere
				TextColumn::make('gender.name')
					->label('Anteprima')
					->searchable()
					->sortable(),

				// Colors
				TextColumn::make('colors.name')
					->wrap()
					->label('Colori')
					->searchable()
					->sortable(),

				// Unisex
				IconColumn::make('unisex')
					->label('Unisex')
					->boolean()
					->sortable(),
			])
			->filters([
				//
			])
			->actions([
				Tables\Actions\EditAction::make(),
				Tables\Actions\DeleteAction::make()
					->before(fn($record) => self::deleteAction($record)),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make()
				]),
			]);
	}

	public static function getRelations(): array {
		return [
			ColorsRelationManager::class,
		];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListProductTypes::route('/'),
			'create' => Pages\CreateProductType::route('/create'),
			'edit' => Pages\EditProductType::route('/{record}/edit'),
		];
	}

	public static function deleteAction(ProductType $record) {
		Product::where('type_id', $record->id)->forceDelete();
	}
}
