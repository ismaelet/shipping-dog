<?php

namespace App\Filament\Admin\Resources\Orders;

use Filament\Schemas\Schema;
use App\Filament\Admin\Resources\Orders\Pages\ListOrders;
use App\Filament\Admin\Resources\Orders\Pages\EditOrder;
use App\Filament\Admin\Resources\Orders\RelationManagers\OrderItemsRelationManager;
use App\Filament\Admin\Resources\Orders\Schemas\OrderForm;
use App\Filament\Admin\Resources\Orders\Tables\OrdersTable;
use App\Models\Ecommerce\Order;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource {

    /**
     * Property: The model the resource corresponds to
     */
    protected static ?string $model = Order::class;

    /**
     * Property: The icon that should be displayed in the navigation item for the resource
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';

    /**
     * Property: The label of a singular resource
     */
    protected static ?string $label = 'Ordine';

    /**
     * Property: The label of a plural resource
     */
    protected static ?string $pluralLabel = 'Ordini';

    /**
     * Property: The group the resource should be displayed in the navigation
     */
    protected static string | \UnitEnum | null $navigationGroup = 'Vendite';

    /**
     * Determine whether the resource can be created
     */
    public static function canCreate(): bool {
        return false;
    }

    /**
     * Determine whether the resource can be deleted
     */
    public static function canDelete(Model $record): bool {
        return false;
    }

    /**
     * The form for creating or updating records
     */
    public static function form(Schema $schema): Schema {
        return OrderForm::configure($schema);
    }

    /**
     * The table for the resource
     */
    public static function table(Table $table): Table {
        return OrdersTable::configure($table);
    }

    /**
     * Get the pages available to the resource.
     * 
     * @return array
     */
    public static function getPages(): array {
        return [
            'index' => ListOrders::route('/'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    /**
     * Get the relations available to the resource.
     * 
     * @return array
     */
    public static function getRelations(): array {
        return [
            OrderItemsRelationManager::class,
        ];
    }
}
