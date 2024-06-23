<?php

namespace App\Filament\Resources\ProductTypeResource\RelationManagers;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductType;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class ColorsRelationManager extends RelationManager {

	protected static string $relationship = 'colors';

	protected static ?string $title = 'Colori';

	public function form(Form $form): Form {
		return $form
			->schema([
				Forms\Components\TextInput::make('name')
					->required()
					->maxLength(255),
			]);
	}

	public function table(Table $table): Table {
		return $table
			->recordTitleAttribute('name')
			->columns([
				Tables\Columns\TextColumn::make('name')
					->searchable(),
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
						fn (Select $select) => $select->placeholder('Seleziona i colori da collegare'),
					)
					->after(function (array $data) {
						$typeId = $this->getOwnerRecord()->id;

						foreach ($data['recordId'] as $recordId) {
							$color = ProductColor::find($recordId);

							DB::update("UPDATE products SET colors = CONCAT(colors, ',$color->tag') WHERE type_id = ?", [$typeId]);
							DB::update("UPDATE products SET colors = TRIM(LEADING ',' FROM colors) WHERE type_id = ?", [$typeId]);
						}
					}),
			])
			->actions([
				Tables\Actions\DetachAction::make()
					->after(function ($record) {
						$typeId = $this->getOwnerRecord()->id;

						DB::update("UPDATE products SET colors = CONCAT(',', colors, ',') WHERE type_id = ?", [$typeId]);
						DB::update("UPDATE products SET colors = REPLACE(colors, ',$record->tag,', ',') WHERE type_id = ?", [$typeId]);
						DB::update("UPDATE products SET colors = TRIM(BOTH ',' FROM colors) WHERE type_id = ?", [$typeId]);
					}),
			])
			->bulkActions([
				Tables\Actions\DetachBulkAction::make()
					->after(function ($records) {
						$typeId = $this->getOwnerRecord()->id;

						foreach ($records as $record) {
							DB::update("UPDATE products SET colors = CONCAT(',', colors, ',') WHERE type_id = ?", [$typeId]);
							DB::update("UPDATE products SET colors = REPLACE(colors, ',$record->tag,', ',') WHERE type_id = ?", [$typeId]);
							DB::update("UPDATE products SET colors = TRIM(BOTH ',' FROM colors) WHERE type_id = ?", [$typeId]);
						}
					}),
			])
			->inverseRelationship('types');
	}
}
