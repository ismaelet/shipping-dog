<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\PaymentMethod;
use App\Models\Ecommerce\ShippingMethod;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class OrderResource extends Resource {

	/**
	 * The model the resource corresponds to.
	 * 
	 * @var string
	 */
	protected static ?string $model = Order::class;

	/**
	 * The icon of the resource.
	 * 
	 * @var string
	 */
	protected static ?string $navigationIcon = 'icon-orders';

	/**
	 * The label of a singular resource.
	 * 
	 * @var string
	 */
	protected static ?string $label = 'Ordine';

	/**
	 * The label of a plural resource.
	 * 
	 * @var string
	 */
	protected static ?string $pluralLabel = 'Ordini';

	/**
	 * The group in which the resource's navigation item will be displayed.
	 * 
	 * @var string
	 */
	protected static ?string $navigationGroup = 'Gestione Vendite';

	/**
	 * Determine whether the resource can be created.
	 * 
	 * @return bool
	 */
	public static function canCreate(): bool {
		return false;
	}

	/**
	 * Determine whether the resource can be deleted.
	 * 
	 * @param \Illuminate\Database\Eloquent\Model $record
	 * @return bool
	 */
	public static function canDelete(Model $record): bool {
		return false;
	}

	/**
	 * The form for creating or updating records.
	 * 
	 * @param \Filament\Forms\Form $form
	 * @return \Filament\Forms\Form
	 */
	public static function form(Form $form): Form {
		return $form
			->schema([
				Actions::make([
					FormAction::make('send-email')
						->label('Reinvia e-mail')
						->icon('icon-send')
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
				]),

				// Split the form into two columns
				Split::make([

					// First column
					Grid::make()
						->schema([
							// Order items
							Fieldset::make('Articoli')
								->schema([
									View::make('filament.forms.orders.order-items-recap'),
								])
								->columns(1)
								->grow(true),

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
										->required(),

									TextInput::make('city')
										->label('Città')
										->placeholder('Città')
										->required()
										->columnSpan(['lg' => 2]),

									TextInput::make('zip')
										->label('CAP')
										->placeholder('CAP')
										->required()
										->length(5),

									TextInput::make('region')
										->label('Regione')
										->placeholder('Regione')
										->required()
										->maxLength(2),

									TextInput::make('email')
										->label('E-mail')
										->placeholder('E-mail')
										->required()
										->suffixIcon('icon-email')
										->columnSpanFull(),

									TextInput::make('phone')
										->label('Telefono')
										->placeholder('Telefono')
										->required()
										->suffixIcon('icon-phone')
										->columnSpanFull(),
								])->columns(['lg' => 4]),
						]),

					// Second column
					Grid::make()
						->schema([

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

									TextInput::make('token')
										->label('Codice della transazione')
										->placeholder('Codice della transazione')
										->readOnly()
										->columnSpanFull(),

									TextInput::make('tracking')
										->label('Numero di tracking')
										->placeholder('Numero di tracking')
										->readOnly()
										->columnSpanFull(),
								])
								->columns(1)
								->grow(true),

							// Shipping
							Fieldset::make('Spedizione')
								->schema([
									Select::make('shipping_method_id')
										->label('Metodo di spedizione')
										->options(
											ShippingMethod::all()->pluck('name', 'id')
										)
										->required()
								])->columns(1),

							// Payment
							Fieldset::make('Pagamento')
								->schema([
									Select::make('payment_method_id')
										->label('Metodo di pagamento')
										->options(
											PaymentMethod::all()->pluck('name', 'id')
										)
										->required()
								])
								->columns(1),
						])
						->grow(false),
				])
					->from('lg')
			])
			->columns(1);
	}

	/**
	 * The table for the resource.
	 * 
	 * @param \Filament\Tables\Table $table
	 * @return \Filament\Tables\Table
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
					->toggledHiddenByDefault()
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
				TextColumn::make('status')
					->label('Stato')
					->sortable()
					->badge()
					->color(function (int $state): string {
						$tag = config('ecommerce.order.statuses')[$state]['tag'];

						return 'status-' . $tag;
					})
					->formatStateUsing(function (int $state): string {
						return config('ecommerce.order.statuses')[$state]['label'];
					}),

				// Whether the customer is registered
				IconColumn::make('user_id')
					->label('Ha account')
					->boolean()
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
			->defaultSort('order_id', 'desc')

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
			->actions([
				ViewAction::make()
					->color('neutral'),
				Tables\Actions\EditAction::make()
			])

			// The bulk actions that should be available for the table.
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([]),
			]);
	}

	/**
	 * Get the pages available to the resource.
	 * 
	 * @return array
	 */
	public static function getPages(): array {
		return [
			'index' => Pages\ListOrders::route('/'),
			'edit' => Pages\EditOrder::route('/{record}/edit'),
		];
	}
}
