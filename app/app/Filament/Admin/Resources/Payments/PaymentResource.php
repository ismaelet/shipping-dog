<?php

namespace App\Filament\Admin\Resources\Payments;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Filament\Admin\Resources\Payments\Pages\CreatePayment;
use App\Filament\Admin\Resources\Payments\Pages\EditPayment;
use App\Filament\Admin\Resources\Payments\Pages\ListPayments;
use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\Payment;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = Payment::class;

    /**
     * The icon of the resource.
     *
     * @var string
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    /**
     * The label of a singular resource.
     */
    protected static ?string $label = 'Pagamento';

    /**
     * The label of a plural resource.
     */
    protected static ?string $pluralLabel = 'Pagamenti';

    /**
     * The group in which the resource's navigation item will be displayed.
     *
     * @var string
     */
    protected static string|\UnitEnum|null $navigationGroup = 'Utenti e Negozi';

    /**
     * The sort order of the resource's navigation item.
     */
    protected static ?int $navigationSort = 3;

    /**
     * The form for creating records.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Seller
                Select::make('seller_id')
                    ->label('Negozio')
                    ->placeholder('Seleziona un negozio')
                    ->relationship('seller', 'name')
                    ->searchable()
                    ->required(),

                // Amount
                TextInput::make('value')
                    ->label('Importo')
                    ->numeric()
                    ->placeholder('Inserisci l\'importo')
                    ->inputMode('decimal')
                    ->required()
                    ->suffix('€'),

                // Other transfer
                Checkbox::make('other')
                    ->label('Trasferenza esterna')
                    ->default(false),
            ])->columns(1);
    }

    /**
     * Get the badge value for the resource.
     */
    public static function getNavigationBadge(): ?string
    {
        return Seller::where('request', true)->count();
    }

    /**
     * Get the badge color for the resource.
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'primary' : 'neutral';
    }

    /**
     * The table for listing records.
     */
    public static function table(Table $table): Table
    {
        return $table
            // Columns
            ->columns([
                // ID
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                // Seller
                TextColumn::make('seller.name')
                    ->label('Negozio')
                    ->placeholder('Negozio non trovato')
                    ->searchable()
                    ->sortable(),

                // Amount
                TextColumn::make('value')
                    ->label('Importo')
                    ->money(currency: 'EUR')
                    ->sortable(),

                // Other transfer
                IconColumn::make('other')
                    ->label('Trasferenza esterna')
                    ->boolean(),

                // Date
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d-m-Y')
                    ->sortable(),
            ])

            // Sort
            ->defaultSort('id', 'desc')

            // Actions
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    /**
     * Get the pages available to the resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
