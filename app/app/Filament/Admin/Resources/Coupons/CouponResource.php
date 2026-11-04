<?php

namespace App\Filament\Admin\Resources\Coupons;

use App\Filament\Admin\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Admin\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Admin\Resources\Coupons\Pages\ListCoupons;
use App\Models\Ecommerce\Coupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    /**
     * The model the resource corresponds to.
     */
    protected static ?string $model = Coupon::class;

    /**
     * The icon of the resource.
     *
     * @var string
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-percent';

    /**
     * The label of a singular resource.
     */
    protected static ?string $label = 'Codice sconto';

    /**
     * The label of a plural resource.
     */
    protected static ?string $pluralLabel = 'Codici sconto';

    /**
     * The group in which the resource's navigation item will be displayed.
     *
     * @var string
     */
    protected static string|\UnitEnum|null $navigationGroup = 'Vendite';

    /**
     * The sort order of the resource's navigation item.
     */
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Codice')
                    ->columnSpanFull()
                    ->default(uniqid())
                    ->required(),

                TextInput::make('mod_us')
                    ->label('Percentuale nostra')
                    ->default(0)
                    ->suffix('%')
                    ->required(),

                TextInput::make('mod_them')
                    ->label('Percentuale negozio')
                    ->default(0)
                    ->suffix('%')
                    ->required(),

                TextInput::make('flat_us')
                    ->label('Fisso nostro')
                    ->default(0)
                    ->suffix('€')
                    ->required(),

                TextInput::make('flat_them')
                    ->label('Fisso negozio')
                    ->default(0)
                    ->suffix('€')
                    ->required(),

                Select::make('seller_id')
                    ->label('Applica solo al negozio')
                    ->relationship('seller', 'name')
                    ->searchable()
                    ->columnSpanFull(),

                Checkbox::make('permanent')
                    ->label('Riusabile'),

                Checkbox::make('shipping')
                    ->label('Spedizione gratis'),

                Checkbox::make('only_one')
                    ->label('Applicabile solo al primo prodotto'),

                Checkbox::make('one_per_acccount')
                    ->label('Usabile solo una volta per utente'),

                DatePicker::make('time_start')
                    ->label('Attivo dal')
                    ->default(now())
                    ->required(),

                DatePicker::make('time_end')
                    ->label('Fino al')
                    ->default(now()->addYear())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Codice')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mod_us')
                    ->label('Percentuale nostra')
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('mod_them')
                    ->label('Percentuale negozio')
                    ->suffix('%')
                    ->sortable(),

                IconColumn::make('permanent')
                    ->label('Riusabile')
                    ->boolean(),

                IconColumn::make('shipping')
                    ->label('Spedizione gratis')
                    ->boolean()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),

                TextColumn::make('time_start')
                    ->label('Attivo dal')
                    ->dateTime('d-m-Y')
                    ->sortable(),

                TextColumn::make('time_end')
                    ->label('Fino al')
                    ->dateTime('d-m-Y')
                    ->sortable(),
            ])

            // The default sort column and direction
            ->defaultSort('id', 'desc')

            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
