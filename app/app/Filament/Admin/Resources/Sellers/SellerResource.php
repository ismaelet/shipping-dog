<?php

namespace App\Filament\Admin\Resources\Sellers;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\Sellers\Pages\ListSellers;
use App\Filament\Admin\Resources\Sellers\Pages\EditSeller;
use App\Filament\Admin\Resources\SellerResource\Pages;
use App\Helpers\FileHelper;
use App\Models\Ecommerce\Seller;
use Dompdf\FrameDecorator\Text;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SellerResource extends Resource {

    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = Seller::class;

    /**
     * The icon of the resource.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';

    /**
     * The label of a singular resource.
     */
    protected static ?string $label = 'Negozio';

    /**
     * The label of a plural resource.
     */
    protected static ?string $pluralLabel = 'Negozi';

    /**
     * The group in which the resource's navigation item will be displayed.
     */
    protected static string | \UnitEnum | null $navigationGroup = 'Utenti e Negozi';

    /**
     * The sort order of the resource's navigation item.
     */
    protected static ?int $navigationSort = 2;

    /**
     * Get the display value of the resource navigation badge.
     */
    public static function getNavigationBadge(): ?string {
        return static::getModel()::isAwaiting()->count();
    }

    /**
     * Get the display color of the resource navigation badge.
     */
    public static function getNavigationBadgeColor(): ?string {
        return static::getNavigationBadge() > 0 ? 'primary' : 'gray';
    }

    /**
     * The form for creating or updating records.
     */
    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Section::make([
                    // Price
                    Fieldset::make('Dati del Negozio')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nome')
                                ->placeholder('Nome del negozio')
                                ->required()
                                ->columnSpanFull(),
                            Checkbox::make('custom')
                                ->label('Negozio personalizzato')
                                ->columnSpanFull(),
                        ]),

                    // Address
                    Fieldset::make('Dati di Pagamento')
                        ->relationship('payment_request')
                        ->visible(fn(Seller $record): bool => (bool) $record->payment_request)
                        ->schema([
                            TextInput::make('paypal')
                                ->label('Indirizzo PayPal')
                                ->placeholder('Indirizzo PayPal')
                                ->default('-')
                                ->columnSpanFull(),
                        ])
                ])
            ]);
    }

    /**
     * Get the query for the resource.
     */
    public static function getEloquentQuery(): Builder {
        return self::$model::query()
            ->select('stores.*')
            ->selectRaw('earned - payed + other_transfers as to_be_paid');
    }

    /**
     * The table for listing records.
     */
    public static function table(Table $table): Table {
        return $table

            // Columns
            ->columns([
                // ID
                TextColumn::make('id')
                    ->searchable()
                    ->label('ID')
                    ->sortable(),

                // Nome
                TextColumn::make('name')
                    ->placeholder('-')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                // Earned
                TextColumn::make('earned')
                    ->label('Guadagnato')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->money(currency: 'EUR'),

                // Paid
                TextColumn::make('payed')
                    ->label('Pagato')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->money(currency: 'EUR'),

                // Other transactions
                TextColumn::make('other_transfers')
                    ->label('Altro')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->money(currency: 'EUR'),

                // To be paid
                TextColumn::make('to_be_paid')
                    ->label('Da pagare')
                    ->sortable()
                    ->money(currency: 'EUR'),

                // Request
                IconColumn::make('request')
                    ->label('Richiesta di pagamento')
                    ->boolean()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),

                // To be paid
                TextColumn::make('payment_request.paypal')
                    ->label('PayPal')
                    ->default('-')
                    ->searchable()
                    ->sortable(),

                // Status
                ToggleColumn::make('status')
                    ->label('Attivo')
                    ->sortable(),

            ])

            // The default sort column and direction
            ->defaultSort('id', 'desc')

            // Filters
            ->filters([
                //
            ])

            // Actions
            ->recordActions([
                ActionGroup::make([
                    Action::make('view-shop')
                        ->label('Visualizza')
                        ->icon('heroicon-o-eye')
                        ->url(fn(Seller $record): string => route('seller.show', $record))
                        ->openUrlInNewTab(true),
                    Action::make('login')
                        ->icon('heroicon-o-arrow-left-end-on-rectangle')
                        ->color('info')
                        ->action(function (Seller $record) {
                            Auth::login($record->user);

                            return to_route('user-dashboard');
                        }),
                    Action::make('download-receipts')
                        ->label('Scarica ricevuta')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn(Seller $record): bool => $record->request)
                        ->action(
                            fn(Seller $record): BinaryFileResponse => FileHelper::download('documents/payments/Ricevuta_' . $record->id . '.pdf')
                        ),
                    Action::make('download-communication')
                        ->label('Scarica comunicazione')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn(Seller $record): bool => $record->request)
                        ->action(
                            fn(Seller $record): BinaryFileResponse => FileHelper::download('documents/payments/Comunicazione_' . $record->id . '.pdf')
                        ),
                    Action::make('remove-request')
                        ->label('Rimuovi richiesta')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->visible(fn(Seller $record) => $record->request)
                        ->action(fn(Seller $record) => $record->update(['request' => false])),
                    DeleteAction::make(),
                ])
            ])

            // Bulk actions
            ->toolbarActions([]);
    }

    /**
     * Get the pages available to the resource.
     */
    public static function getPages(): array {
        return [
            'index' => ListSellers::route('/'),
            'edit' => EditSeller::route('/{record}/edit'),
        ];
    }
}
