<?php

namespace App\Filament\Admin\Resources\Orders;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\View;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use App\Filament\Admin\Resources\Orders\Pages\ListOrders;
use App\Filament\Admin\Resources\Orders\Pages\EditOrder;
use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\Orders\RelationManagers\OrderItemsRelationManager;
use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\PaymentMethod;
use App\Models\Ecommerce\ShippingMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

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
        return $schema
            ->components([
                Actions::make([
                    Action::make('send-email')
                        ->label('Reinvia e-mail')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function (Order $record): bool {
                            Mail::to($record->address->email)->queue(new OrderStatusMail($record));

                            if (true) {
                                Notification::make()
                                    ->title('E-mail inviata')
                                    ->body('A ' . $record->address->email . ' correttamente')
                                    ->success()
                                    ->send();

                                return true;
                            }

                            Notification::make()
                                ->title('Trasmesso')
                                ->body('Errore durante l\'invio dell\'email')
                                ->danger()
                                ->send();

                            return false;
                        })
                ])->columnSpanFull(),

                // Price
                Fieldset::make('Prezzo')
                    ->schema([
                        View::make('filament.forms.orders.order-items-recap'),
                    ])->columnSpanFull(),

                // Address
                Fieldset::make('Destinatario')
                    ->relationship('address')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Nome completo')
                            ->placeholder('Nome e cognome')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('address')
                            ->label('Indirizzo')
                            ->placeholder('Indirizzo')
                            ->columnSpan(['lg' => 3]),

                        TextInput::make('number')
                            ->label('Numero')
                            ->placeholder('Numero'),

                        TextInput::make('city')
                            ->label('Città')
                            ->placeholder('Città')
                            ->columnSpan(['lg' => 2]),

                        TextInput::make('zip')
                            ->label('CAP')
                            ->placeholder('CAP')
                            ->length(5),

                        TextInput::make('region')
                            ->label('Regione')
                            ->placeholder('Regione')
                            ->maxLength(2),

                        TextInput::make('email')
                            ->label('E-mail')
                            ->placeholder('E-mail')
                            ->required()
                            ->suffixIcon('heroicon-o-envelope')
                            ->columnSpanFull(),

                        TextInput::make('phone')
                            ->label('Telefono')
                            ->placeholder('Telefono')
                            ->required()
                            ->suffixIcon('heroicon-o-phone')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),

                // Order details
                Fieldset::make('Dati dell\'ordine')
                    ->schema([
                        Select::make('status')
                            ->label('Stato')
                            ->options(
                                array_combine(
                                    array_keys(config('ecommerce.order.statuses')),
                                    array_column(config('ecommerce.order.statuses'), 'label')
                                )
                            )
                            ->required()
                            ->columnSpanFull(),
                    ])->columnSpanFull(),

                // Shipping
                Fieldset::make('Spedizione')
                    ->schema([
                        Select::make('shipping_method_id')
                            ->label('Metodo di spedizione')
                            ->options(
                                ShippingMethod::all()->pluck('name', 'id')
                            )
                            ->required(),

                        TextInput::make('tracking')
                            ->label('Numero di tracking')
                            ->placeholder('Numero di tracking')
                            ->readOnly()
                            ->columnSpanFull(),

                        Toggle::make('exchange')
                            ->label('Exchange')
                            ->columnSpanFull(),
                    ]),

                // Payment
                Fieldset::make('Pagamento')
                    ->schema([
                        Select::make('payment_method_id')
                            ->label('Metodo di pagamento')
                            ->options(
                                PaymentMethod::all()->pluck('name', 'id')
                            )
                            ->required(),

                        TextInput::make('token')
                            ->label('Codice della transazione')
                            ->placeholder('Codice della transazione')
                            ->readOnly()
                            ->columnSpanFull(),
                    ])
            ]);
    }

    /**
     * The table for the resource
     */
    public static function table(Table $table): Table {
        return $table

            // The query that should be used to fetch records. This query
            ->query(function (): Builder {
                return Order::leftJoin('addresses', 'orders.id', '=', 'addresses.order_id')
                    ->select('orders.id as order_id', 'orders.*')
                    ->with('order_items', 'order_items.product_with_trashed', 'order_items.type', 'order_items.position', 'order_items.type.colors', 'order_items.type.sizes', 'order_items.color', 'order_items.size', 'order_items.logo');
            })

            // The columns that should be displayed in the table.
            ->columns([
                // ID
                TextColumn::make('order_id')
                    ->searchable()
                    ->sortable()
                    ->numeric()
                    ->label('ID'),

                // Recipient
                TextColumn::make('address.full_address')
                    ->label('Destinatario')
                    ->placeholder('-')
                    ->listWithLineBreaks()
                    ->wrap()
                    ->searchable(query: function (Builder $query, $search) {
                        $query->where('full_name', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('address', 'like', '%' . $search . '%')
                            ->orWhere('city', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->sortable(['full_name', 'first_name', 'last_name', 'address', 'email']),

                // Products
                TextColumn::make('order_items.full_name')
                    ->state(function (Order $record): array {
                        return $record->order_items->pluck('full_name')->toArray();
                    })
                    ->label('Articoli')
                    ->listWithLineBreaks()
                    ->wrap()
                    ->toggleable()
                    ->searchable(query: function (Builder $query, $search) {
                        $query->whereHas('order_items', function (Builder $query) use ($search) {
                            $query
                                ->WhereHas('product', function (Builder $query) use ($search) {
                                    $query->where('name', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('seller', function (Builder $query) use ($search) {
                                    $query->where('name', 'like', '%' . $search . '%');
                                });
                        });
                    })
                    ->placeholder('nessun articolo'),

                // Total price
                TextColumn::make('price')
                    ->label('Totale')
                    ->money(currency: 'EUR')
                    ->sortable(),

                // Status
                SelectColumn::make('status')
                    ->label('Stato')
                    ->sortable()
                    ->label('Stato')
                    ->options(
                        array_combine(
                            array_keys(config('ecommerce.order.statuses')),
                            array_map(fn($status) => '<span style="color: var(--status-' . $status['tag'] . '-400); text-wrap: nowrap;">' . $status['label'] . '</span >', config('ecommerce.order.statuses'))
                        )
                    )
                    ->searchableOptions()
                    ->allowOptionsHtml(),
                // TextColumn::make('status')
                // 	->label('Stato')
                // 	->sortable()
                // 	->badge()
                // 	->color(function (int $state): string {
                // 		$tag = config('ecommerce.order.statuses')[$state]['tag'];

                // 		return 'status-' . $tag;
                // 	})
                // 	->formatStateUsing(function (int $state): string {
                // 		return config('ecommerce.order.statuses')[$state]['label'];
                // 	}),

                // Whether the customer is registered
                IconColumn::make('user_id')
                    ->label('Ha account')
                    ->boolean()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->state(function (Order $record): bool {
                        return (bool) $record->user_id;
                    })
                    ->sortable(),

                // Tracking
                TextColumn::make('tracking')
                    ->placeholder('senza tracking')
                    ->label('Tracking')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->searchable()
                    ->sortable(),

                // Created at
                TextColumn::make('created_at')
                    ->label('Creato il')
                    ->dateTime('d-m-Y')
                    ->sortable(),
            ])

            // The default sort column and direction.
            ->defaultSort('created_at', 'desc')

            // The filters that should be available for the table.
            ->filters([
                SelectFilter::make('status')
                    ->label('Stato')
                    ->options(array_combine(array_keys(config('ecommerce.order.statuses')), array_column(config('ecommerce.order.statuses'), 'label')))
                    ->default((array_values(array_filter(array_keys(config('ecommerce.order.statuses'))))))
                    ->multiple(),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(1)

            // The actions that should be available for the resources.
            ->recordActions([
                ViewAction::make()
                    ->color('info'),
                EditAction::make()
            ])

            // The bulk actions that should be available for the table.
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
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
