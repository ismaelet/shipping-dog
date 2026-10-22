<?php

namespace App\Models\Ecommerce\Design;

use App\Helpers\FileHelper;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPhoto extends Model {

    /**
     * The table associated with the model
     */
    protected $table = 'home_pics';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'design_id',
        'first_name',
        'last_name',
        'product_id',
        'type_id',
    ];

    /**
     * Attribute: Get the full name of the customer
     */
    public function fullName(): Attribute {
        $firstName = $this->first_name;
        $lastName = $this->last_name || $this->last_name == '-' ? '' : substr($this->last_name, 0, 1) . '.';

        return Attribute::make(
            get: fn() => implode(' ', [$firstName, $lastName])
        );
    }

    /**
     * Attribute: Get the server image path
     */
    public function serverImage(): Attribute {
        return Attribute::make(
            get: fn() => $this->image()
        );
    }

    /**
     * Relationship: The design that this customer photo is associated with
     */
    public function design(): BelongsTo {
        return $this->belongsTo(Design::class);
    }

    /**
     * Relationship: The type that this customer photo is associated with
     */
    public function type(): BelongsTo {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the storage path of the image
     */
    public function storagePath(): string {
        return public_path('storage/users/testimonials/' . $this->id . '.webp');
    }

    /**
     * Get the URL of the image
     */
    public function image(): string {
        if (file_exists($this->storagePath())) {
            return FileHelper::storageAsset('users/testimonials/' . $this->id . '.webp');
        }

        return FileHelper::storageAsset('users/testimonials/' . $this->id . '.jpg');
    }
}
