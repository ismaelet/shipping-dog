<?php

namespace App\Models\Ecommerce;

use App\Helpers\FileHelper;
use App\Models\Ecommerce\Design\CustomerPhoto;
use App\Models\Ecommerce\Design\DesignTag;
use App\Models\Ecommerce\Design\LogoGroup;
use App\Services\Files\DesignPreviewGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Vite;

class Design extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'name',
        'user_id',
        'status',
        'ratio',
        'sort',
        'color',
        'default_color_id',
        'logo_group_id',
    ];

    public function full_name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name.' - '.$this->id,
        );
    }

    /**
     * Delete the design and the related products
     */
    public function delete()
    {
        $this->products()->delete();

        return parent::delete();
    }

    /**
     * Get the design's seller
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'user_id', 'user_id');
    }

    /**
     * Get the design's main
     */
    public function main_product(): HasOne
    {
        return $this->hasOne(Product::class)
            ->where('status', true)
            ->orderBy('type_id');
    }

    /**
     * Get the design's products
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class)
            ->where('status', true)
            ->orderBy('type_id');
    }

    /**
     * Get the design's tags
     */
    public function tags(): HasOne
    {
        return $this->hasOne(DesignTag::class);
    }

    /**
     * Get the design's customer photos
     */
    public function customerPhotos(): HasMany
    {
        return $this->hasMany(CustomerPhoto::class);
    }

    /**
     * Get the design's logo group
     */
    public function logo_group(): BelongsTo
    {
        return $this->belongsTo(LogoGroup::class);
    }

    /**
     * Ge the desing's url, that is the url of the main product
     */
    public function url(): ?string
    {
        return $this->main_product?->url();
    }

    /**
     * Get the design's time string
     */
    public function createdAtString(): string
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

    /**
     * Get the count of products
     */
    public function productCount(): int
    {
        return $this->products->count();
    }

    /**
     * Check if the design is liked by the logged user
     */
    // public function auth_user_like(): HasOne {
    //     return $this->hasOne(Like::class)
    //         ->where('user_id', Auth::id());
    // }

    // public function isLiked(): bool {
    //     return $this->auth_user_like ? true : false;
    // }

    /**
     * Get the design's image
     */
    public function image(int $size = 400): string
    {
        $file = $size == 400 ? 'designs/previews/'.$this->id.'_m.webp' : 'designs/previews/'.$this->id.'.webp';

        if (! FileHelper::exists($file)) {
            app(DesignPreviewGenerator::class)->generate($this);
        }

        if (FileHelper::exists($file)) {
            return storage_asset($file);
        }

        return Vite::image('placeholder.webp');
    }

    public function originalImage(): ?string
    {

        if (FileHelper::exists($file = 'designs_vector/'.$this->id.'.ai')) {
            return storage_asset($file);
        }

        if (FileHelper::exists($file = 'designs_vector/'.$this->id.'.pdf')) {
            return storage_asset($file);
        }

        if (FileHelper::exists($file = 'designs_originals/'.$this->id.'.png')) {
            return storage_asset($file);
        }

        return null;
    }

    public function originalDarkImage(): ?string
    {

        if (FileHelper::exists($file = 'designs_vector/'.$this->id.'_dark.ai')) {
            return storage_asset($file);
        }

        if (FileHelper::exists($file = 'designs_vector/'.$this->id.'_dark.pdf')) {
            return storage_asset($file);
        }

        if (FileHelper::exists($file = 'designs_originals/'.$this->id.'_dark.png')) {
            return storage_asset($file);
        }

        return null;
    }

    /**
     * Delete the design and clean the related data
     */
    public function deleteAndClean(bool $permanently = false): void
    {
        // Delete products
        foreach ($this->products as $product) {
            $product->deleteAndClean($permanently);
        }

        if ($permanently) {
            // Delete tags
            foreach ($this->tags as $tag) {
                $tag->delete();
            }

            // Delete customer photos
            if ($this->customer_photos) {
                foreach ($this->customer_photos as $customerPhoto) {
                    $customerPhoto->delete();
                }
            }

            // Delete design
            $this->delete();
        } else {
            // Disable design
            $this->status = 0;
            $this->save();
        }
    }
}
