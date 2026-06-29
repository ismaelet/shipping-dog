<?php

namespace App\Livewire\Admin;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\Framework\Configuration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductLog extends Component {

    /**
     * Property: The orders collection
     */
    public ?Collection $orderItems;

    /**
     * Property: The order items in inventory
     */
    public ?Collection $inventoryItems = null;

    /**
     * Property: The first order in the current log
     */
    public ?Order $firstOrder = null;

    /**
     * Property: The time from which to start showing orders
     */
    public ?Order $previousOrder = null;

    /**
     * Property: Whether to show all orders
     */
    public bool $showAll = false;

    /**
     * Property: Whether to show printed items
     */
    public bool $showPrinted = false;

    /**
     * Property: Whether to show items in cart
     */
    public bool $showInCart = false;

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount() {
        $this->firstOrder = Order::find(
            Configuration::where('name', 'product_log_start_from')->value('value')
        );

        $this->loadOrderItems();
    }

    /**
     * Lifecycle hook: Render the component
     */
    public function render() {
        return view('livewire.admin.product-log', [
            'designGroups' => $this->getDesignGroups(),
            'productGroups' => $this->getProductGroups(),
        ]);
    }

    /**
     * Event handler: Handle 'firstOrderUpdated' event
     */
    #[On('firstOrderUpdated')]
    public function onFirstOrderUpdated(array $data): void {
        $this->firstOrder = Order::find($data['first_order_id'] ?? null);

        $this->loadOrderItems();
    }

    /**
     * Event handler: Handle 'cleanOrderLog' event
     */
    #[On('cleanOrderLog')]
    public function onCleanOrderLog(): void {
        $this->orderItems = null;
        $this->inventoryItems = null;
    }

    /**
     * Event handler: Handle 'includeReadyUpdated' event
     */
    #[On('includeReadyUpdated')]
    public function onIncludeReadyUpdated(array $data): void {
        $this->showAll = (bool) $data['include_ready'];

        $this->loadOrderItems();
    }

    /**
     * Event handler: Handle 'showPrintedUpdated' event
     */
    #[On('showPrintedUpdated')]
    public function onShowPrintedUpdated(array $data): void {
        $this->showPrinted = (bool) $data['show_printed'];

        $this->loadOrderItems();
    }

    /**
     * Event handler: Handle 'showInCartUpdated' event
     */
    #[On('showInCartUpdated')]
    public function onShowInCartUpdated(array $data): void {
        $this->showInCart = (bool) $data['show_in_cart'];

        $this->loadOrderItems();
    }

    /**
     * Helper method: Load orders from the database
     */
    protected function loadOrderItems(): void {
        if (!$this->firstOrder) {
            $this->orderItems = null;
            $this->inventoryItems = null;

            return;
        }

        // Get the orders
        $query = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('addresses', 'addresses.order_id', '=', 'orders.id')
            ->join('sellers', 'sellers.id', '=', 'order_items.seller_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('product_types', 'product_types.id', '=', 'order_items.type_id')
            ->where('orders.created_at', '>=', $this->firstOrder->created_at)
            ->where('orders.status', '>=', EcommerceHelper::statusFromTag('confirmed'))
            ->select(
                'orders.id as order_id',
                'orders.created_at as confirmedTime',
                'order_items.*',
                'sellers.name as storeName',
                'addresses.full_name as customerName',
                'products.name as productName',
                'product_types.name as productTypeName',
            )
            ->with([
                'type',
                'type.sizes',
                'type.colors',
                'color',
                'size',
                'position',
                'logo',
                'product',
                'product_with_trashed',
                'product_with_trashed.type',
                'product_with_trashed.type.colors',
                'product_with_trashed.type.sizes',
                'seller',
                'design',
                'order.shipping_method',
            ]);

        if (!$this->showAll) {
            $query->where('orders.status', EcommerceHelper::statusFromTag('confirmed'));
        }

        $this->applyPrintedAndCartFilters($query);

        $this->orderItems = (clone $query)
            ->where('inventory', false)
            ->get();

        $this->inventoryItems = (clone $query)
            ->where('inventory', true)
            ->get();
    }

    /**
     * Helper method: Apply filters for printed and in-cart items
     */
    protected function applyPrintedAndCartFilters(Builder $query): void {
        if (!$this->showPrinted && !$this->showInCart) {
            $query->where(function ($query) {
                $query
                    ->where('order_items.is_printed', false)
                    ->orWhere('order_items.is_in_cart', false);
            });

            return;
        }

        if ($this->showPrinted) {
            $query->where('order_items.is_printed', true);
        }

        if ($this->showInCart) {
            $query->where('order_items.is_in_cart', true);
        }
    }

    /**
     * Helper method: Check if there are order items
     */
    public function hasOrderItems(): bool {
        return $this->orderItems?->isNotEmpty() ?? false;
    }

    /**
     * Helper method: Check if there are inventory items
     */
    public function hasInventoryItems(): bool {
        return $this->inventoryItems?->isNotEmpty() ?? false;
    }

    /**
     * Helper method: Get design groups for the view
     * 
     * Groups order items by seller and then by design
     */
    protected function getDesignGroups(): SupportCollection {
        return collect($this->orderItems ?? [])
            ->filter(fn(OrderItem $item) => $item->seller)
            ->groupBy(function (OrderItem $item) {
                if ($item->isFree()) {
                    return 'free';
                }

                if ($item->hasDiscount()) {
                    return 'discounted';
                }

                return 'seller-' . $item->seller_id;
            })
            ->map(function (SupportCollection $sellerItems, string $groupKey) {
                if ($groupKey === 'free') {
                    return [
                        'seller' => null,
                        'sellerName' => '!! Gratis !!',
                        'designs' => $this->getDesignsForSeller($sellerItems),
                    ];
                }

                if ($groupKey === 'discounted') {
                    return [
                        'seller' => null,
                        'sellerName' => '!! Scontati !!',
                        'designs' => $this->getDesignsForSeller($sellerItems),
                    ];
                }

                return [
                    'seller' => $sellerItems->first()?->seller,
                    'sellerName' => $sellerItems->first()?->seller?->name,
                    'designs' => $this->getDesignsForSeller($sellerItems),
                ];
            })
            ->values();
    }

    /**
     * Helper method: Get designs for a specific seller
     * 
     * Groups order items by design for a given seller
     */
    protected function getDesignsForSeller(SupportCollection $sellerItems): SupportCollection {
        return $sellerItems
            ->groupBy(fn(OrderItem $item) => $item->design_id ?? 'custom')
            ->map(function (SupportCollection $designItems) {
                $design = $designItems->first()?->design;

                return [
                    'design' => $design,
                    'title' => $this->getDesignTitle($designItems),
                    'imageUrl' => $design?->originalImage(),
                    'items' => $this->getTypeColorSizePositionGroups($designItems),
                ];
            })
            ->values();
    }

    /**
     * Helper method: Get product groups for the view
     * 
     * Groups order items by product type, then by color and size
     */
    protected function getProductGroups(): SupportCollection {
        return collect($this->orderItems ?? [])
            ->filter(fn(OrderItem $item) => $item->type)
            ->sortBy('type_id')
            ->groupBy('type_id')
            ->map(function (SupportCollection $typeItems) {
                $type = $typeItems->first()?->type;

                return [
                    'type' => $type,
                    'items' => $this->getColorSizeGroups($typeItems),
                ];
            })
            ->values();
    }

    /**
     * Helper method: Get groups of order items by type, color, size, and position
     * 
     * Groups order items that have the same type, color, size, and position
     */
    protected function getTypeColorSizePositionGroups(SupportCollection $items): SupportCollection {
        return $items
            ->filter(fn(OrderItem $item) => $item->type && $item->color && $item->size)
            ->sortBy([
                ['type_id', 'asc'],
                ['color_id', 'asc'],
                ['size_id', 'asc'],
                ['position_id', 'asc'],
            ])
            ->groupBy(function (OrderItem $item) {
                return implode('-', [
                    $item->type_id,
                    $item->color_id,
                    $item->size_id,
                    $item->position_id,
                    $this->isLocalDelivery($item) ? 'local-delivery' : 'shipping',
                ]);
            })
            ->map(function (SupportCollection $group) {
                $first = $group->first();
                $isLocalDelivery = $this->isLocalDelivery($first);

                return [
                    'type' => $first->type,
                    'color' => $first->color,
                    'size' => $first->size,
                    'position' => $first->position,
                    'design' => $first->design,
                    'isLocalDelivery' => $isLocalDelivery,
                    'suffix' => $this->localDeliverySuffix($isLocalDelivery),
                    'orderItems' => $group->values(),
                ];
            })
            ->values();
    }

    /**
     * Helper method: Get groups of order items by color and size
     * 
     * Groups order items that have the same color and size
     */
    protected function getColorSizeGroups(SupportCollection $items): SupportCollection {
        return $items
            ->filter(fn(OrderItem $item) => $item->color && $item->size)
            ->sortBy([
                ['color_id', 'asc'],
                ['size_id', 'asc'],
            ])
            ->groupBy(function (OrderItem $item) {
                return implode('-', [
                    $item->color_id,
                    $item->size_id,
                    $this->isLocalDelivery($item) ? 'local-delivery' : 'shipping',
                ]);
            })
            ->map(function (SupportCollection $group) {
                $first = $group->first();
                $isLocalDelivery = $this->isLocalDelivery($first);

                return [
                    'type' => $first->type,
                    'color' => $first->color,
                    'size' => $first->size,
                    'isLocalDelivery' => $isLocalDelivery,
                    'suffix' => $this->localDeliverySuffix($isLocalDelivery),
                    'orderItems' => $group->values(),
                ];
            })
            ->values();
    }

    /**
     * Helper method: Check if an order item is for local delivery
     */
    protected function isLocalDelivery(OrderItem $item): bool {
        return $item->order?->shipping_method?->isLocalDelivery() === true;
    }

    /**
     * Helper method: Get the suffix for local delivery
     */
    protected function localDeliverySuffix(bool $isLocalDelivery): string {
        return $isLocalDelivery ? ' (ritiro in sede)' : '';
    }

    /**
     * Helper method: Get the title for a design group
     * 
     * If the design exists, returns "ID - Name", otherwise returns the product name or a default message
     */
    protected function getDesignTitle(SupportCollection $items): string {
        $design = $items->first()->design;

        if ($design) {
            return $design->id . ' - ' . $design->name;
        }

        return $items->first()?->product?->name ?: ' Prodotto non trovato ';
    }
}
