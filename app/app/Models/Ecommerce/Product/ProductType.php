<?php

namespace App\Models\Ecommerce\Product;

use App\Helpers\FileHelper;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\Type\Measure;
use App\Services\Files\ProductPreviewProcessor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class ProductType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tag',
        'name',
        'names',
        'gender_id',
        'sibling_id',
        'variant_of',
        'sizes',
        'positions',
        'category',
        'colors',
        'hidden',
        'sort',
        'preorder',
    ];

    /**
     * Relationship: Get the type's gender
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(ProductGender::class, 'gender_id');
    }

    /**
     * Relationship: Get the type's gender
     */
    public function main_type(): BelongsTo
    {
        return $this->belongsTo(self::class, 'variant_of', 'id');
    }

    /**
     * Relationship: Get the colors associated with the product type
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(ProductColor::class);
    }

    /**
     * Relationship: Get the positions associated with the product type
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(ProductPosition::class)->wherePivot('printable', true);
    }

    /**
     * Relationship: Get the positions associated with the product type
     */
    public function all_positions(): BelongsToMany
    {
        return $this->belongsToMany(ProductPosition::class)->withPivot('printable');
    }

    /**
     * Relationship: Get the positions with an unique image associated with the product type
     */
    public function unique_positions(): BelongsToMany
    {
        return $this->belongsToMany(ProductPosition::class)->where('base_of', false);
    }

    /**
     * Relationship: Get the positions associated with the product type
     */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(ProductSize::class);
    }

    /**
     * Relationship: Get the sibling
     */
    public function sibling(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'sibling_id');
    }

    /**
     * Relationship: Get the default price
     */
    public function price(): HasOne
    {
        return $this->hasOne(ProductTypePrice::class, 'type_id');
    }

    /**
     * Relationship: Get the other product types that are variants of this type
     */
    public function variants(): HasMany
    {
        return $this->hasMany(self::class, 'variant_of', 'id');
    }

    /**
     * Relationship: Get measures
     */
    public function measures(): HasMany
    {
        return $this->hasMany(Measure::class, 'type_id', 'id')->whereNot('size', 'mat');
    }

    /**
     * Relationship: Get the type's mockup sizes
     */
    public function mockup_sizes(): HasMany
    {
        return $this->hasMany(ProductTypeMockupSize::class, 'type_id', 'id');
    }

    /**
     * Relationship: Get the type's mockup sizes
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'type_id', 'id');
    }

    /**
     * The list of the type's colors as array
     */
    public function colorList(): array
    {
        return explode(',', $this->colors);
    }

    /**
     * The list of the type's colors as collection
     */
    public function colorCollection(): Collection
    {
        return ProductColor::whereIn('tag', $this->colorList())->orderBy('reorder')->get();
    }

    /**
     * The list of the type's colors that are publicly available
     */
    public function publicColors(): Collection
    {
        return ProductColor::whereIn('tag', $this->colorList())
            ->where('hidden', false)
            ->orderBy('reorder')
            ->orderBy('id')
            ->get();
    }

    public function shownType(): self
    {
        if ($this->sibling) {
            return $this->sibling;
        }

        if ($this->main_type) {
            return $this->main_type;
        }

        return $this;
    }

    /**
     * The infos of this type
     */
    public function info(): HasOne
    {
        return $this->hasOne(ProductTypeInfo::class, 'product_type_id', 'id');
    }

    /**
     * Get the type's image
     */
    public function image(?int $color_id = null, ?int $position_id = null, string $suffix = ''): ?string
    {
        $color = $color_id ? ProductColor::find($color_id) : $this->colorCollection()->first();
        $position = $position_id ? ProductPosition::find($position_id) : $this->positions->first();

        if (! $color) {
            Log::error('ProductType::image() - No color found for type: '.$this->tag.' and color_id: '.$color_id);
        }

        if (FileHelper::exists($file = 'product_types/mockups/'.$this->tag.'/'.$color->tag.'-'.$position->basicPositionId().$suffix.'.webp')) {
            return $file;
        }

        if (FileHelper::exists($file = 'product_types/mockups/'.$this->tag.'/'.$color->tag.'-'.$position->basicPositionId().$suffix.'.png')) {
            return $file;
        }

        if (FileHelper::exists($file = 'product_types/mockups/'.$this->tag.'/'.$color->tag.$suffix.'.png')) {
            return $file;
        }

        return null;
    }

    /**
     * Get the type's details image
     */
    public function details(?int $color_id = null, ?int $position_id = null): ?string
    {
        return $this->image($color_id, $position_id, '-det');
    }

    /**
     * Get the rasterized type's image.
     */
    public function rasterizedImage(ProductColor|int $color, ProductPosition|int $position): ?string
    {
        $color = $color instanceof ProductColor
            ? $color
            : ProductColor::find($color);

        $position = $position instanceof ProductPosition
            ? $position
            : ProductPosition::find($position);

        if (! $color || ! $position) {
            return null;
        }

        return app(ProductPreviewProcessor::class)->emptyImage(
            type: $this,
            color: $color,
            position: $position,
        );
    }
}
