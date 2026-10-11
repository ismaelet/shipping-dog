<?php

namespace App\Filament\Admin\Resources\Orders\Tables;

use App\Models\Ecommerce\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable {
    public static function configure(Table $table): Table {
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
}
