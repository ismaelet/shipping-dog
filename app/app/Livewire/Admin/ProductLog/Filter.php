<?php

namespace App\Livewire\Admin\ProductLog;

use App\Models\Ecommerce\Order;
use App\Models\Framework\Configuration;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Filter extends Component implements HasSchemas {

    use InteractsWithSchemas;

    /**
     * Property: The orders collection
     */
    #[Reactive]
    public ?Collection $orderItems = null;

    /**
     * Property: The first order in the current log
     */
    public ?Order $firstOrder = null;

    /**
     * Filament property: The data for the form
     */
    public ?array $data = [];

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount(): void {
        $this->form->fill();
    }

    /**
     * Lifecycle hook: Render the component
     */
    public function render(): View {
        return view('livewire.admin.product-log.filter');
    }

    /**
     * Filament method: Define the form schema
     */
    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                Fieldset::make('body')
                    ->label('Mostrando ' . ($this->orderItems?->count() ?: 0) . " " . ($this->orderItems?->count() == 1 ? 'articolo' : 'articoli'))
                    ->schema([
                        Select::make('first_order_id')
                            ->label('Carica da')
                            ->getSearchResultsUsing(fn(string $search): array => Order::query()
                                ->where('orders.id', 'like', "%{$search}%")
                                ->orWhereHas('address', fn($query) => $query->where('full_name', 'like', "%{$search}%"))
                                ->limit(20)
                                ->orderBy('orders.id', 'desc')
                                ->with('address')
                                ->get()
                                ->mapWithKeys(fn($order) => [
                                    $order->id => $order->idCode() . ' - ' . $order->address?->full_name
                                ])
                                ->all())

                            ->getOptionLabelUsing(function ($value): ?string {
                                $order = Order::with('address')->find($value);

                                if (!$order) {
                                    return null;
                                }

                                return $order->idCode() . ' - ' . $order->address?->full_name;
                            })

                            ->searchable()
                            ->default(fn(): ?int => $this->firstOrder?->id ?: null)
                            ->required(),
                        Action::make('load')
                            ->label('Carica')
                            ->action('load')
                            ->color('info')
                            ->button(),
                        Grid::make(3)
                            ->schema([
                                Checkbox::make('include_ready')
                                    ->label('Includi ordini pronti o spediti')
                                    ->default(false)
                                    ->reactive(),
                                Checkbox::make('show_printed')
                                    ->label('Mostra articoli in coda di stampa')
                                    ->default(false)
                                    ->reactive(),
                                Checkbox::make('show_in_cart')
                                    ->label('Mostra articoli in carrello')
                                    ->default(false)
                                    ->reactive(),
                            ])
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->visible(fn(): bool => $this->orderItems?->count() > 0)
                            ->schema([
                                Action::make('copy')
                                    ->label('Copia tutto')
                                    ->color('primary')
                                    ->outlined()
                                    ->icon('heroicon-o-clipboard-document')
                                    ->action('copy')
                                    ->button(),
                                Action::make('clean')
                                    ->label('Pulisci log')
                                    ->color('danger')
                                    ->action('clean')
                                    ->outlined()
                                    ->icon('heroicon-o-trash')
                                    ->button(),
                            ])
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Filament method: Load orders starting from the selected order ID
     */
    public function load(): void {
        $firstOrderId = $this->form->getState()['first_order_id'];
        $firstOrder = Order::find($firstOrderId);

        if ($firstOrder) {
            Configuration::updateOrCreate(
                ['name' => 'product_log_start_from'],
                ['value' => $firstOrder->id],
            );
        }

        $this->firstOrder = $firstOrder;
        $this->form->fill();
        $this->dispatch('firstOrderUpdated', ['first_order_id' => $firstOrderId]);
    }

    /**
     * Filament method: Copy data from the selected order ID
     */
    public function copy(): void {
        $this->dispatch('copyAll');
    }

    /**
     * Filament method: Clean the order log
     */
    public function clean(): void {
        $lastOrder = Order::orderBy('id', 'desc')
            ->first();

        Configuration::updateOrCreate(
            ['name' => 'product_log_start_from'],
            ['value' => ($lastOrder?->id ?? 0) + 1],
        );

        $this->firstOrder = null;
        $this->form->fill();
        $this->dispatch('cleanOrderLog');
    }

    /**
     * Update handler: Handle changes to the 'include_ready' checkbox
     */
    public function updatedDataIncludeReady(bool $value): void {
        $this->dispatch('includeReadyUpdated', ['include_ready' => $value]);
    }

    /**
     * Update handler: Handle changes to the 'show_printed' checkbox
     */
    public function updatedDataShowPrinted(bool $value): void {
        $this->dispatch('showPrintedUpdated', ['show_printed' => $value]);
    }

    /**
     * Update handler: Handle changes to the 'show_in_cart' checkbox
     */
    public function updatedDataShowInCart(bool $value): void {
        $this->dispatch('showInCartUpdated', ['show_in_cart' => $value]);
    }
}
