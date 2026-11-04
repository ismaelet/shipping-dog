<?php

namespace App\Models\Ecommerce\Design;

use App\Helpers\FileHelper;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DesignCollection extends Model
{
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
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'user_id');
    }

    /**
     * Relationship: Get the designs in the collection
     */
    public function designs(): HasMany
    {
        return $this->hasMany(Design::class, 'collection', 'id');
    }

    /**
     * Relationship: Get the main design of the collection
     */
    public function mainDesign(): HasOne
    {
        return $this->hasOne(Design::class, 'collection', 'id')->oldest();
    }

    /**
     * Purchasable products of the designs currently in the collection
     */
    public function purchasableProducts(): Builder
    {
        return Product::query()
            ->select('products.*')
            ->where('products.status', 1)
            ->where('products.deleted', 0)
            ->whereHas('design', fn ($query) => $query
                ->where('user_id', $this->user_id)
                ->where('collection', $this->id));
    }

    /**
     * Check whether the collection belongs to the given seller.
     */
    public function belongsToSeller(Seller $seller): bool
    {
        return $this->user_id === $seller->user_id;
    }

    /**
     * Get the URL for the collection
     */
    public function url(): string
    {
        return route('seller.show.collection', [
            'seller' => $this->user_id,
            'collection' => $this->id,
        ]);
    }

    /**
     * Get the stored cover paths, newest format first (legacy covers are jpg)
     */
    public function coverPaths(int $size = 400): array
    {
        $suffix = $size === 400 ? '_m' : '';

        return [
            'shops/collections/'.$this->id.$suffix.'.webp',
            'shops/collections/'.$this->id.$suffix.'.jpg',
        ];
    }

    /**
     * Whether the collection has an uploaded cover
     */
    public function hasCover(): bool
    {
        return collect($this->coverPaths(1024))->contains(fn (string $path) => FileHelper::exists($path));
    }

    /**
     * Get the image URL for the collection
     */
    public function image(int $size = 400): ?string
    {
        foreach ($this->coverPaths($size) as $path) {
            if (FileHelper::exists($path)) {
                return storage_asset($path);
            }
        }

        return $this->mainDesign?->image($size);
    }

    /**
     * Delete every stored cover file
     */
    public function deleteCover(): void
    {
        foreach ([400, 1024] as $size) {
            foreach ($this->coverPaths($size) as $path) {
                FileHelper::delete($path);
            }
        }
    }
}
