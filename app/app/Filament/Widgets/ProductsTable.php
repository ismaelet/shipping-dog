<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use App\Models\Ecommerce\Design;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class ProductsTable extends TableWidget {

	protected static ?string $heading = 'Prodotti del mese';

	protected static ?string $pollingInterval = null;

	protected static bool $isLazy = false;

	protected int | string | array $columnSpan = [
		'lg' => 5,
	];

	public function table(Table $table): Table {
		return $table
			->query(
				Design::orderByDesc('recent_sales')
					->limit(7)
			)
			->columns([
				TextColumn::make('recent_sales')
					->numeric()
					->label('Vendite'),
				TextColumn::make('name')
					->label('Prodotto')
					->wrap(),
				TextColumn::make('seller.name')
					->label('Negozio')
					->wrap(),
			])
			->paginated(false);
	}
}
