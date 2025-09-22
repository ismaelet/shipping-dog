<?php

namespace App\Filament\Admin\Resources\CustomerPhotos;

use App\Filament\Admin\Resources\CustomerPhotos\Pages\CreateCustomerPhoto;
use App\Filament\Admin\Resources\CustomerPhotos\Pages\EditCustomerPhoto;
use App\Filament\Admin\Resources\CustomerPhotos\Pages\ListCustomerPhotos;
use App\Filament\Admin\Resources\CustomerPhotos\Schemas\CustomerPhotoForm;
use App\Filament\Admin\Resources\CustomerPhotos\Tables\CustomerPhotosTable;
use App\Models\Ecommerce\Design\CustomerPhoto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerPhotoResource extends Resource {

	/**
	 * Property: The model the resource corresponds to
	 */
	protected static ?string $model = CustomerPhoto::class;

	/**
	 * Property: The icon that should be displayed in the navigation item for the resource
	 */
	protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

	/**
	 * Property: The label of a singular resource
	 */
	// protected static ?string $recordTitleAttribute = 'id';

	/**
	 * Property: The label of a singular resource
	 */
	protected static ?string $label = 'Foto';

	/**
	 * Property: The label of a plural resource
	 */
	protected static ?string $pluralLabel = 'Foto clienti';

	/**
	 * Property: The group the resource should be displayed in the navigation
	 */
	protected static string | \UnitEnum | null $navigationGroup = 'Prodotti';

	/**
	 * Property: The sort order of the resource in the navigation
	 */
	protected static ?int $navigationSort = 3;

	public static function form(Schema $schema): Schema {
		return CustomerPhotoForm::configure($schema);
	}

	public static function table(Table $table): Table {
		return CustomerPhotosTable::configure($table);
	}

	public static function getRelations(): array {
		return [
			//
		];
	}

	public static function getPages(): array {
		return [
			'index' => ListCustomerPhotos::route('/'),
			'create' => CreateCustomerPhoto::route('/create'),
			'edit' => EditCustomerPhoto::route('/{record}/edit'),
		];
	}
}
