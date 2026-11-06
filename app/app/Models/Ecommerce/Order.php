<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Mail\OrderStatusMail;
use App\Models\Ecommerce\Seller\Payment;
use App\Models\NexiPayment;
use App\Models\SentEmail;
use App\Models\User;
use App\Services\GlsService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Mailer\Exception\UnexpectedResponseException;

/**
 * Represents a shopping order in the e-commerce system.
 */
class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'shipping_method_id',
        'payment_method_id',
        'code',
        'price',
        'quantity',
        'credit_ship',
        'shipping',
        'status',
        'step',
        'courier_notes',
        'shipping_notes',
        'has_errors',
        'created_at',
        'updated_at',
        'token',
        'tracking',
        'exchange',
    ];

    /**
     * Update the order's order_items status when the order is updated.
     */
    public function update(array $attributes = [], array $options = []): bool
    {
        // Synchronize the order_items status with the order status
        if (isset($attributes['status'])) {
            $this->order_items->each->update([
                'status' => $attributes['status'],
            ]);
        }

        // Synchronize the order_items created_at with the order created_at
        if (isset($attributes['created_at'])) {
            $this->order_items->each->update([
                'created_at' => $attributes['created_at'],
            ]);
        }

        return parent::update($attributes, $options);
    }

    protected function estimatedDelivery(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->shipping_method->deliveryDateString($this->created_at, $this->hasLongDelivery())
        );
    }

    /**
     * Get the order's user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order's shipping method.
     */
    public function shipping_method(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * Get the order's payment method.
     */
    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the order's address.
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the order's coupon.
     */
    public function coupon(): HasOne
    {
        return $this->hasOne(Coupon::class, 'code', 'code');
    }

    /**
     * Get the order's change request.
     */
    public function change_request(): HasOne
    {
        return $this->hasOne(ChangeRequest::class, 'new_order_id');
    }

    /**
     * Get the order's order_items.
     */
    public function order_items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the Nexi payment attempts for this order.
     */
    public function nexiPayments(): HasMany
    {
        return $this->hasMany(NexiPayment::class);
    }

    /**
     * Get the order's code
     */
    public function idCode(): string
    {
        return '#'.str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the order's created at date as a string
     */
    public function createdAtString(): string
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    /**
     * Get the order's created at date in a human readable Italian format (e.g. "13 settembre 2025")
     */
    public function humanDate(): string
    {
        return Carbon::parse($this->created_at)->locale('it')->isoFormat('D MMMM YYYY');
    }

    /**
     * Get the main title of the order: the first item's name, plus the number of other items
     */
    public function mainTitle(): string
    {
        $first = $this->order_items->first();

        if (! $first) {
            return 'Nessun prodotto';
        }

        $title = $first->product_with_trashed?->name ?: 'Articolo rimosso';
        $others = $this->order_items->count() - 1;

        return $others > 0 ? $title.' +'.$others : $title;
    }

    /**
     * Get the order's sub-title (e.g. "Taglia S · 2 articoli")
     */
    public function subTitle(): string
    {
        $first = $this->order_items->first();
        $count = (int) $this->order_items->sum('quantity');
        $parts = [];

        if ($first && $this->order_items->count() === 1) {
            $parts[] = $first->size && $first->type?->sizes->count() > 1 ? 'Taglia '.$first->size->name : 'Taglia unica';
        }

        $parts[] = $count.($count === 1 ? ' articolo' : ' articoli');

        return implode(' · ', $parts);
    }

    /**
     * Check if the order is empty (has no order items)
     */
    public function isEmpty(): bool
    {
        return ! $this->order_items()->exists();
    }

    /**
     * Get the order's payment URL
     */
    public function paymentUrl(): string
    {
        return match ((int) $this->payment_method_id) {
            1 => route('paypal.orders.create', [
                'order_id' => $this->id,
            ]),

            2 => route('nexi.orders.create', [
                'order' => $this->id,
            ]),

            default => throw new \InvalidArgumentException(
                'Metodo di pagamento non valido.'
            ),
        };
    }

    /**
     * Get the order's recap URL
     */
    public function recapUrl(): string
    {
        return route('orders.show', ['order' => $this->id]);
    }

    /**
     * Get the order's recap URL with the security token
     */
    public function recapUrlWithToken(): string
    {
        return route('orders.show', ['order' => $this->id, 'token' => $this->token]);
    }

    /**
     * Check if the order has a long delivery time
     */
    public function hasLongDelivery(): bool
    {
        foreach ($this->order_items as $orderItem) {
            if ($orderItem->product && ($orderItem->product->type?->preorder || $orderItem->color?->preorder)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the order's order_items as a string.
     */
    public function orderItemsString(): string
    {
        $orderItemsString = '';

        if (! $this->order_items->count()) {
            return 'Nessun prodotto';
        }

        foreach ($this->order_items as $orderItem) {
            $orderItemsString .= $orderItem->full_name.'<br>';
        }

        return $orderItemsString;
    }

    /**
     * Get the order's step data.
     */
    public function stepData(): array
    {
        return config('ecommerce.cart.steps')[$this->step];
    }

    /**
     * Get the order's step tag.
     */
    public function stepTag(): string
    {
        return $this->stepData()['tag'];
    }

    /**
     * Get the order's status data.
     */
    public function statusData(): array
    {
        return config('ecommerce.order.statuses')[$this->status];
    }

    /**
     * Get the order's status tag.
     */
    public function statusTag(): string
    {
        return $this->statusData()['tag'];
    }

    /**
     * Get the order's status name.
     */
    public function statusName(): string
    {
        return $this->statusData()['label'];
    }

    /**
     * Get the order's status color.
     */
    public function statusColor(): string
    {
        return $this->statusData()['color'];
    }

    /**
     * Get the status icon.
     */
    public function statusIcon(): string
    {
        return $this->statusData()['icon'];
    }

    /**
     * Attribute: Status Label
     */
    public function statusLabel(): string
    {
        return view('components.ecommerce.order-status-label', [
            'order' => $this,
        ])->render();
    }

    /**
     * ~ Attribute: Order Items Price
     */
    public function orderItemsPrice(): float
    {
        $orderItemsPrice = 0;

        foreach ($this->order_items as $orderItem) {
            $orderItemsPrice += $orderItem->total_amount_to_pay;
        }

        return $orderItemsPrice;
    }

    /**
     * Attribute: Full Order Items Price
     */
    public function fullOrderItemsPrice(): float
    {
        $price = 0;

        foreach ($this->order_items as $orderItem) {
            $price += $orderItem->total_amount_before_discount;
        }

        return $price;
    }

    /**
     * The used credit for the order
     */
    public function usedCredit(): float
    {
        $shippingCredit = $this->credit_ship ? $this->grossShippingFee() : 0;
        $orderItemsCredit = $this->usedOrderItemsCredit();

        return $shippingCredit + $orderItemsCredit;
    }

    /**
     * Attribute: Get the total credit used for order items.
     */
    public function usedOrderItemsCredit(): float
    {
        $credit = 0;

        foreach ($this->order_items as $orderItem) {
            if ($orderItem->credit) {
                $credit += $orderItem->total_amount_after_discount;
            }
        }

        return $credit;
    }

    /**
     * Get the order's net shipping fee.
     */
    public function shippingFee(): float
    {
        return $this->credit_ship ? 0 : $this->grossShippingFee();
    }

    /**
     * Get the order's gross shipping fee.
     */
    public function grossShippingFee(): float
    {
        return $this->shipping_method ? $this->shipping_method->price($this->order_items->sum('quantity')) : 0;
    }

    /**
     * Get the order's total discount.
     */
    public function discount(): float
    {
        return $this->orderItemsDiscount() + $this->shippingDiscount();
    }

    /**
     * Get the order's total order_items discount.
     */
    public function orderItemsDiscount(): float
    {
        $orderItemsDiscount = 0;

        foreach ($this->order_items as $orderItem) {
            $orderItemsDiscount += $orderItem->total_discount;
        }

        return $orderItemsDiscount;
    }

    /**
     * ~ Attribute: Shipping Discount
     */
    public function shippingDiscount(): float
    {
        return $this->credit_ship ? $this->grossShippingFee() : 0;
    }

    /**
     * ~ Attribute: Price String
     */
    public function priceString(): string
    {
        return EcommerceHelper::formatPrice($this->price);
    }

    /**
     * ~ Attribute: Shipping Fee String
     */
    public function shippingFeeString(): string
    {
        return EcommerceHelper::formatPrice($this->shippingFee());
    }

    /**
     * Get the order's total discount formatted as a price string.
     */
    public function discountString(): string
    {
        return EcommerceHelper::formatPrice($this->discount());
    }

    /**
     * Get the order's order_items discount formatted as a price string.
     */
    public function orderItemsDiscountString(): string
    {
        return EcommerceHelper::formatPrice($this->orderItemsDiscount());
    }

    /**
     * Advance the order to the next step or to a specific step.
     */
    public function advance(?string $advanceToStepTag = null): self
    {
        // If no specific step is given, advance to the next step
        if (! $advanceToStepTag) {
            // The current order step
            $currentStep = $this->step;

            // The page step
            $pageStepTag = explode('.', Route::currentRouteName())[1] ?? null;
            $pageStep = EcommerceHelper::stepFromTag($pageStepTag);

            // Advance to the next step, if the current order step is equal to the page step
            if ($currentStep == $pageStep) {
                $this->step++;
                $this->save();
            }
        } else {
            // Advance to the given step
            $advanceToStep = EcommerceHelper::stepFromTag($advanceToStepTag);

            if ($advanceToStep) {
                $this->step = $advanceToStep;
                $this->save();
            }
        }

        return $this;
    }

    /**
     * Update the order's quantity and price.
     */
    public function updateOrderItems(): self
    {
        $this->updateQuantity();
        $this->updatePrice();

        return $this;
    }

    /**
     * Update the order's quantity.
     */
    public function updateQuantity(): self
    {
        $this->quantity = $this->order_items()->sum('quantity');
        $this->save();

        return $this;
    }

    /**
     * Update the order's price.
     */
    public function updatePrice(): self
    {
        $orderItemsPrice = $this->orderItemsPrice();
        $shippingFee = $this->shippingFee();

        $this->update([
            'price' => $orderItemsPrice + $shippingFee,
            'shipping' => $shippingFee,
        ]);

        return $this;
    }

    /**
     * Try to apply a coupon to the order.
     */
    public function applyCoupon(string $couponCode): string|false
    {
        $coupon = Coupon::firstWhere('code', $couponCode);

        if (! $coupon) {
            return 'Il codice sconto non esiste';
        }

        // Check if the coupon exists
        if ($error = $coupon->isValid()) {
            return $error;
        }

        // Remove shipping cost if the coupon has free shipping
        if ($coupon->shipping) {
            $this->update([
                'shipping_method_id' => ShippingMethod::FREE_SHIPPING_METHODS[0],
            ]);
        }

        // Apply the coupon to the order items
        foreach ($this->order_items as $orderItem) {
            $sellerDiscount = 0;
            $ourDiscount = 0;

            // Check if the order matches the coupon requirements
            $matchesDesign = ! $coupon->design_id || $coupon->design_id == $orderItem->design_id;
            $matchesSeller = ! $coupon->seller_id || $coupon->seller_id == $orderItem->seller_id;
            $matchesType = ! $coupon->type_id || $coupon->type_id == $orderItem->type_id;
            $matchesColor = ! $coupon->color_id || $coupon->color_id == $orderItem->color_id;

            // Check if the order item matches the coupon requirements
            if (! $matchesDesign || ! $matchesSeller || ! $matchesType || ! $matchesColor) {
                continue;
            }

            // Calculate the discount amount
            if ($coupon->nogain) {
                $totalDiscount = $orderItem->unit_seller_earnings_before_reduction;
                $gainReduction = $orderItem->unit_seller_earnings_before_reduction;
            } else {
                // Precentage discount
                if ($coupon->mod_us3 && $this->quantity > 2) {
                    $ourDiscountPercentage = $coupon->mod_us3;
                } elseif ($coupon->mod_us2 && $this->quantity > 1) {
                    $ourDiscountPercentage = $coupon->mod_us2;
                } else {
                    $ourDiscountPercentage = $coupon->mod_us;
                }

                if ($coupon->mod_them3 && $this->quantity > 2) {
                    $sellerDiscountPercentage = $coupon->mod_them3;
                } elseif ($coupon->mod_them2 && $this->quantity > 1) {
                    $sellerDiscountPercentage = $coupon->mod_them2;
                } else {
                    $sellerDiscountPercentage = $coupon->mod_them;
                }

                // Fixed discount
                if ($coupon->flat_us3 && $this->quantity > 2) {
                    $ourDiscountFixed = $coupon->flat_us3;
                } elseif ($coupon->flat_us2 && $this->quantity > 1) {
                    $ourDiscountFixed = $coupon->flat_us2;
                } else {
                    $ourDiscountFixed = $coupon->flat_us;
                }

                if ($coupon->flat_them3 && $this->quantity > 2) {
                    $sellerDiscountFixed = $coupon->flat_them3;
                } elseif ($coupon->flat_them2 && $this->quantity > 1) {
                    $sellerDiscountFixed = $coupon->flat_them2;
                } else {
                    $sellerDiscountFixed = $coupon->flat_them;
                }

                // Total discount
                $ourDiscount = ($orderItem->grossProductPrice() * $ourDiscountPercentage / 100) + $ourDiscountFixed;
                $sellerDiscount = ($orderItem->grossProductPrice() * $sellerDiscountPercentage / 100) + $sellerDiscountFixed;

                // Calulate the total discount and reduced gain
                $totalDiscount = $ourDiscount + $sellerDiscount;
                $gainReduction = $sellerDiscount;
            }

            $orderItem->update([
                'unit_discount' => $totalDiscount / $orderItem->quantity,
                'total_discount' => $totalDiscount,
                'unit_seller_reduction' => $gainReduction / $orderItem->quantity,
                'total_seller_reduction' => $gainReduction,
            ]);

            // Break the loop if the coupon is only valid for one item
            if ($coupon->only_one) {
                break;
            }
        }

        $this->update([
            'code' => $coupon->code,
        ]);

        $this->updatePrice();

        return false;
    }

    /**
     * Remove the coupon from the order.
     */
    public function removeCoupon(): void
    {
        // Remove the coupon code from the order
        $this->update([
            'code' => null,
        ]);

        // Reset the order items discount
        foreach ($this->order_items as $orderItem) {
            $orderItem->update([
                'total_discount' => 0,
                'gain_disc' => 0,
            ]);
        }

        // Update the order price
        $this->updatePrice();
    }

    /**
     * Confirm the order.
     */
    public function confirm(bool $sendNotification = true, bool $updateDate = true, bool $createShipment = true): void
    {
        // Forget the order ID
        session()->forget('cart_id');

        // Update the order
        if ($updateDate) {
            $this->update([
                'created_at' => now(),
            ]);
        }

        $this->update([
            'status' => EcommerceHelper::statusFromTag('confirmed'),
        ]);

        // Remove the used credit from the seller's credit
        if ($this->usedCredit()) {
            Payment::create([
                'seller_id' => $this->user->seller?->id,
                'value' => -$this->usedCredit(),
                'other' => true,
                'description' => 'Credito utilizzato per acquisto',
            ]);
        }

        // Destroy used coupons
        if ($this->coupon) {
            if ($this->coupon->one_per_acccount) {
                UsedCoupon::create(['coupon_id' => $this->coupon->id, 'user_id' => $this->user_id]);
            }

            if (! $this->coupon->permanent) {
                $this->coupon->delete();
            }
        }

        // Confirm the individual order_items
        $this->confirmOrderItems();

        // Update the sellers balance
        $this->updateSellersBalance();

        // Send the confirmation email
        if ($sendNotification) {
            $this->sendStatusEmail();
        }

        // Send the shipping data
        if ($createShipment) {
            $glsService = new GlsService;
            $glsService->addParcels($this);
        }
    }

    /**
     * Confirm the order's order_items.
     */
    public function confirmOrderItems(): void
    {
        foreach ($this->order_items as $orderItem) {
            // Check if the order is in the inventory
            $inventoryItem = InventoryItem::where('product_id', $orderItem->product_id)
                ->where('color_id', $orderItem->color_id)
                ->where('size_id', $orderItem->size_id)
                ->where('position_id', $orderItem->position_id)
                ->first();

            if ($inventoryItem) {
                $orderItem->update(['inventory' => 1]);
                $inventoryItem->delete();
            }
        }
    }

    /**
     * Update the selle5rs balance after an order is confirmed or cancelled
     */
    private function updateSellersBalance(): void
    {
        $this->order_items->loadMissing('seller');

        $this->order_items
            ->pluck('seller')
            ->filter()
            ->unique('id')
            ->each(function ($seller) {
                $seller->updateBalance();
            });
    }

    /**
     * Cancel an order
     */
    public function cancel(bool $sendNotification = false): void
    {

        // Update the order
        $this->update([
            'status' => EcommerceHelper::statusFromTag('cancelled'),
        ]);

        // Send the status email
        if ($sendNotification) {
            $this->sendStatusEmail();
        }

        // Update the seller's earnings
        $this->updateSellersBalance();
    }

    /**
     * Action: Send the order's status email
     */
    public function sendStatusEmail(bool $forced = false): void
    {
        $type = 'order-status-update-'.$this->statusTag();
        $email = $this->address->email;

        // Check if the email is already sent
        if (
            ! $forced && SentEmail::where('email', $email)
                ->where('type', $type)
                ->exists()
        ) {
            // Log::error("Email already sent for {$email} with type {$type}");
            return;
        }

        // Create a new SentEmail record
        SentEmail::create([
            'email' => $email,
            'type' => $type,
        ]);

        try {
            Mail::to($email)->queue(new OrderStatusMail($this));
        } catch (UnexpectedResponseException $e) {
            Log::info("Email sending failed for {$email}: ".$e->getMessage());
        }
    }

    /**
     * Delete the order.
     */
    public function delete(): bool
    {
        session()->forget('cart_id');

        return parent::delete();
    }

    public static function hasBeenPaid(): Builder
    {
        return self::where('status', '>=', EcommerceHelper::statusFromTag('confirmed'));
    }

    public static function hasNoLabel(): Builder
    {
        return self::join('addresses', 'orders.id', '=', 'addresses.order_id')
            ->where('status', EcommerceHelper::statusFromTag('confirmed'))
            ->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
            ->oldest('orders.created_at')
            ->select('orders.*', 'orders.id as order_id', 'addresses.full_name');
    }

    /**
     * * Static methods
     */
    /**
     * Get the orders that need a label to be printed.
     */
    public static function unlabelled(): Collection
    {
        return self::join('addresses', 'orders.id', '=', 'addresses.order_id')
            ->where('status', EcommerceHelper::statusFromTag('confirmed'))
            ->whereIn('shipping_method_id', ShippingMethod::shippingMethodsWithLabels())
            ->oldest()
            ->select('orders.*', 'addresses.full_name')
            ->get();
    }
}
