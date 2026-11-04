<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\Logo;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductSize;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'design_id',
        'type_id',
        'color_id',
        'size_id',
        'position_id',
        'logo_id',
    ];

    /**
     * Lifecycle hooks
     */
    protected static function booted(): void
    {

        // After creating a record
        static::created(function (self $record) {
            if (! $record->product) {
                $product = Product::where('design_id', $record->design_id)
                    ->where('type_id', $record->type_id)
                    ->first();

                if ($product) {
                    $record->product()->associate($product);
                    $record->save();
                }
            }

            if (! $record->type) {
                if ($record->product) {
                    $record->type()->associate($record->product->type);
                    $record->save();
                }
            }
        });
    }

    /**
     * Get the product that owns the inventory item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the design that owns the inventory item.
     */
    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    /**
     * Get the type that owns the inventory item.
     */
    public function type()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the color that owns the inventory item.
     */
    public function color()
    {
        return $this->belongsTo(ProductColor::class);
    }

    /**
     * Get the size that owns the inventory item.
     */
    public function size()
    {
        return $this->belongsTo(ProductSize::class);
    }

    /**
     * Get the position that owns the inventory item.
     */
    public function position()
    {
        return $this->belongsTo(ProductPosition::class);
    }

    /**
     * Get the logo that owns the inventory item.
     */
    public function logo()
    {
        return $this->belongsTo(Logo::class);
    }
}
