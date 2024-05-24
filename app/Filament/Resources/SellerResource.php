<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerResource\Pages;
use App\Filament\Resources\SellerResource\RelationManagers;
use App\Models\Ecommerce\Seller;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SellerResource extends Resource {

	protected static ?string $model = Seller::class;

	protected static ?string $navigationIcon = 'icon-shops';

	protected static ?string $label = 'Negozio';

	protected static ?string $pluralLabel = 'Negozi';

	protected static ?string $navigationGroup = 'Gestione Utenti';

	protected static ?int $navigationSort = 2;

	public static function getNavigationBadge(): ?string {
		return static::getModel()::isAwaiting()->count();
	}

	public static function getNavigationBadgeColor(): ?string {
		return static::getModel()::count() > 0 ? 'primary' : 'neutral';
	}

	public static function form(Form $form): Form {
		return $form
			->schema([
				//
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->columns([
				//
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
			'index' => Pages\ListSellers::route('/'),
			'create' => Pages\CreateSeller::route('/create'),
			'edit' => Pages\EditSeller::route('/{record}/edit'),
		];
	}
}
