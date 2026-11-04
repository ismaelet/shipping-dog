<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductPosition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'tag',
        'info',
        'editable',
        'depending_on',
        'base_of',
    ];

    /**
     * Relationship with ProductType
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(ProductType::class)->withPivot('printable');
    }

    /**
     * Check if the position has a specific mockup
     */
    public function hasSpecificMockup(): bool
    {
        return ! $this->base_of;
    }

    /**
     * Get the basic position ID
     */
    public function basicPositionId(): int
    {
        return $this->base_of ?: $this->id;
    }
}
