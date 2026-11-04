<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\PaymentMethod;
use App\Models\Ecommerce\ShippingMethod;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Mail;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
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

                            Notification::make()
                                ->title('E-mail inviata')
                                ->body('A '.$record->address->email.' correttamente')
                                ->success()
                                ->send();

                            return true;
                        }),
                ])->columnSpanFull(),

                // Price
                Fieldset::make('Riepilogo')
                    ->schema([
                        View::make('filament.forms.orders.order-items-recap')
                            ->columnSpanFull(),
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
                            ->required()
                            ->columnSpan(['lg' => 3]),

                        TextInput::make('number')
                            ->label('Numero')
                            ->placeholder('Numero')
                            ->required()
                            ->columnSpan(['lg' => 1]),

                        TextInput::make('city')
                            ->label('Città')
                            ->placeholder('Città')
                            ->required()
                            ->columnSpan(['lg' => 2]),

                        TextInput::make('zip')
                            ->label('CAP')
                            ->placeholder('CAP')
                            ->length(5)
                            ->required(),

                        TextInput::make('region')
                            ->label('Regione')
                            ->placeholder('Regione')
                            ->maxLength(2)
                            ->required(),

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
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->columns(['lg' => 4])
                    ->columnSpanFull(),

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
                    ]),
            ]);
    }
}
