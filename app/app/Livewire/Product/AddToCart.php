<?php

namespace App\Livewire\Product;

use App\Helpers\EcommerceHelper;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\ShippingMethod;
use App\Services\Ecommerce\OrderItemAmountCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class AddToCart extends Component {

    /**
     * Property: The product associated with the component
     */
    #[Locked]
    public Product $product;

    /**
     * Property: The quantity of the product to add to the cart
     */
    public int $quantity = 1;

    /**
     * Property: The maximum quantity allowed for the product
     */
    public int $maxQuantity = 999;

    /**
     * Property: The color ID for the product
     */
    public ?int $colorId = null;

    /**
     * Property: The size ID for the product
     */
    public ?int $sizeId = null;

    /**
     * Property: The position ID for the product
     */
    public ?int $positionId = null;

    /**
     * Property: The logo ID for the product
     */
    public ?int $logoId = null;

    /**
     * Lifecycle hook: Render the component view
     */
    public function render(): View {
        return view('livewire.product.add-to-cart');
    }

    /**
     * Action: Add the product to the cart
     */
    public function addToCart(): void {
        // Validate the quantity
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $this->maxQuantity],
        ]);

        // Create the order item
        DB::transaction(function (): void {
            $this->createOrderItem();
        });

        // Dispatch an event to update the cart
        $this->dispatch('update-cart');
        $this->dispatch('order-items-updated');

        // Reset the quantity to 1 after adding to cart
        $this->quantity = 1;
    }

    /**
     * Event handler: Handle updates to the color option
     */
    #[On('change-color')]
    public function updateColor(int $id): void {
        $this->colorId = $id;
    }

    /**
     * Event handler: Handle updates to the size option
     */
    #[On('change-size')]
    public function updateSize(int $id): void {
        $this->sizeId = $id;
    }

    /**
     * Event handler: Handle updates to the position option
     */
    #[On('change-position')]
    public function updatePosition(int $id): void {
        $this->positionId = $id;
    }

    /**
     * Event handler: Handle updates to the logo option
     */
    #[On('change-logo')]
    public function updateLogo(int $id): void {
        $this->logoId = $id;
    }

    /**
     * Helper method: Create an order item for the product and add it to the cart
     */
    private function createOrderItem(): void {
        // Get the cart
        $order = $this->getOrCreateOrder();

        // Get the selected color, or default to the first available color
        $color = $this->product->colors->firstWhere('id', $this->colorId);

        if (!$color) {
            throw new \RuntimeException('Il colore selezionato non è valido per questo prodotto. Prodotto: ' . $this->product->id . '. Colore selezionato: ' . $this->colorId);
        }

        // Check if the order item already exists in the cart
        $orderItem = OrderItem::where('order_id', $order->id)
            ->where('product_id', $this->product->id)
            ->where('color_id', $color->id)
            ->where('size_id', $this->sizeId)
            ->where('position_id', $this->positionId)
            ->where('logo_id', $this->logoId)
            ->where('design_id', $this->product->design_id)
            ->where('type_id', $this->product->type->id)
            ->where('credit', false)
            ->first();

        if ($orderItem) {
            // Update the quantity of the existing order item
            $orderItem->quantity += $this->quantity;

            $amounts = app(OrderItemAmountCalculator::class)->calculate(
                product: $this->product,
                color: $color,
                quantity: $orderItem->quantity,
            );

            $orderItem->update([
                'quantity' => $orderItem->quantity,
                ...$amounts,
            ]);
        } else {
            // Create a new order item
            $amounts = app(OrderItemAmountCalculator::class)->calculate(
                product: $this->product,
                color: $color,
                quantity: $this->quantity,
            );

            OrderItem::create([
                'product_id' => $this->product->id,
                'color_id' => $color->id,
                'size_id' => $this->sizeId,
                'position_id' => $this->positionId,
                'logo_id' => $this->logoId,
                'quantity' => $this->quantity,
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'seller_id' => $this->product->seller->id,
                'design_id' => $this->product->design_id,
                'type_id' => $this->product->type->id,
                'gender_id' => $this->product->type->gender->id,

                ...$amounts,
            ]);
        }

        // Update the cart data
        $order->updateOrderItems();

        // Re-apply the coupon, if present
        if ($order->coupon?->code) {
            $couponCode = $order->coupon->code;

            $order->removeCoupon();
            $order->applyCoupon($couponCode);
        }
    }

    /**
     * Helper method: Get or create the current cart
     */
    private function getOrCreateOrder(): Order {
        if (cart()) {
            $order = cart();

            if ($order->step > EcommerceHelper::stepFromTag('address')) {
                $order->step = EcommerceHelper::stepFromTag('address');
                $order->save();
            }
        } else {
            if (Auth::check()) {
                // If the user is logged in
                $order = Order::create(['user_id' => Auth::id()]);
            } else {
                // If the user is a guest
                $order = Order::create();

                session(['cart_id' => $order->id]);
            }

            $order->shipping_method_id = ShippingMethod::defaultShippingMethodId();
            $order->save();
        }

        return $order;
    }
}
