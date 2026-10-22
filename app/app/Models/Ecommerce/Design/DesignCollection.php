<?php

namespace App\Models\Ecommerce\Design;

use App\Helpers\FileHelper;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DesignCollection extends Model {

    /**
     * Property: The table associated with the model
     */
    protected $table = 'collections';

    /**
     * Property: The attributes that are mass assignable
     */
    protected $fillable = [
        'user_id',
        'name',
    ];

    /**
     * Relationship: Get the seller that owns the collection
     */
    public function seller(): BelongsTo {
        return $this->belongsTo(Seller::class, 'user_id');
    }

    /**
     * Relationship: Get the designs in the collection
     */
    public function designs(): HasMany {
        return $this->hasMany(Design::class, 'collection', 'id');
    }

    /**
     * Relationship: Get the main design of the collection
     */
    public function mainDesign(): HasOne {
        return $this->hasOne(Design::class, 'collection', 'id')->oldest();
    }

    /**
     * Check whether the collection belongs to the given seller.
     */
    public function belongsToSeller(Seller $seller): bool {
        return $this->user_id === $seller->user_id;
    }

    /**
     * Get the URL for the collection
     */
    public function url(): string {
        return route('seller.show.collection', [
            'seller' => $this->user_id,
            'collection' => $this->id,
        ]);
    }

    /**
     * Get the image URL for the collection
     */
    public function image(int $size = 400): ?string {
        $image = $size === 400
            ? '/shops/collections/' . $this->id . '_m.jpg'
            : '/shops/collections/' . $this->id . '.jpg';

        if (FileHelper::exists($image)) {
            return storage_asset($image);
        }

        if ($this->mainDesign) {
            return $this->mainDesign->image($size);
        }

        return null;
    }
}
