<?php

namespace App\Models\Ecommerce;

use App\Http\Controllers\Ecommerce\RefundController;
use App\Mail\ChangeConfirmation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

class ChangeRequest extends Model {

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'old_order_id',
        'new_order_id',
        'step',
        'token',
    ];

    /**
     * The lifecycle hooks for the model
     */
    protected static function booted(): void {
        static::deleting(function (ChangeRequest $changeRequest) {
            $changeRequest->items()->delete();
        });
    }

    /**
     * Get the old cart that owns the change request
     */
    public function old_order(): BelongsTo {
        return $this->belongsTo(Order::class, 'old_order_id');
    }

    /**
     * Get the new cart generated from the change request
     */
    public function new_order(): BelongsTo {
        return $this->belongsTo(Order::class, 'new_order_id');
    }

    /**
     * Get the order items for the change request
     */
    public function items(): HasMany {
        return $this->hasMany(Change::class, 'change_request_id');
    }

    /**
     * Get the only the changes that are a real change, for the change request
     */
    public function items_to_change(): HasMany {
        return $this->hasMany(Change::class, 'change_request_id')->where('is_change', true);
    }

    /**
     * Get the only the changes that are a refund, for the change request
     */
    public function items_to_refund(): HasMany {
        return $this->hasMany(Change::class, 'change_request_id')->where('is_change', false);
    }

    /**
     * Check if the change request is only a refund
     */
    public function isRefundOnly(): bool {
        return !$this->items_to_change->count();
    }

    /**
     * Confirm the change request and create the new order if needed
     */
    public function confirm(): void {
        $this->update(['step' => array_key_last(RefundController::STEPS)]);

        if (!$this->isRefundOnly()) {
            $oldOrder = $this->old_order;

            // Create a new order for the change
            $newOrder = $oldOrder
                ->replicate()
                ->fill([
                    'payment_method_id' => PaymentMethod::where('tag', 'ppl')->first()->id,
                    'shipping_method_id' => ShippingMethod::where('tag', 'pre')->first()->id,
                    'price' => 8.00,
                    'shipping' => 8.00,
                    'quantity' => $this->items_to_change->count(),
                    'status' => 2,
                    'token' => $this->token,
                    'exchange' => true,
                    'tracking' => null,
                ]);

            $newOrder->save();

            $this->update(['new_order_id' => $newOrder->id]);

            // Create the address for the new order
            $oldOrder->address?->replicate()
                ->fill([
                    'order_id' => $newOrder->id,
                ])
                ->save();


            // Create the order items for the new order
            $this->items_to_change->each(function ($item) {
                $oldOrderItem = $item->old_order_item;

                $newOrderItem = $oldOrderItem->replicate()
                    ->fill([
                        'order_id' => $this->new_order_id,
                        'size_id' => $item->new_size_id ?? $oldOrderItem->size_id,
                        'gain' => 0,
                        'gain_disc' => 0,
                        'inventory' => false,
                        'credit' => false,
                        'is_printed' => false,
                        'is_in_production' => false,
                        'fullProductPrice' => 0,
                        'productGain' => 0,
                        'status' => 2,
                    ]);

                $newOrderItem->save();

                $item->update(['new_order_item_id' => $newOrderItem->id]);

                InventoryItem::create([
                    'product_id' => $newOrderItem->product_id,
                    'design_id' => $newOrderItem->design_id,
                    'type_id' => $newOrderItem->type_id,
                    'color_id' => $newOrderItem->color_id,
                    'size_id' => $newOrderItem->size_id,
                    'position_id' => $newOrderItem->position_id,
                    'logo_id' => $newOrderItem->logo_id,
                ]);
            });

            if (!$this->isRefundOnly()) {
                Mail::to($oldOrder->address->email)->send(new ChangeConfirmation());
            }
        }
    }
}
