<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTypeInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_type_id',
        'description',
        'details',
    ];

    /**
     * The product type this info belongs to
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
    }

    /**
     * Get the formatted description.
     */
    public function formattedDescription(): ?string
    {
        return $this->description ? implode(
            '<br>',
            array_map(function ($element) {
                return "&middot; $element";
            }, preg_split('/\r\n|\r|\n/', $this->description)),
        ) : null;
    }

    /**
     * Get the formatted details.
     */
    public function formattedDetails(): ?string
    {
        return $this->details ? implode(
            '<br>',
            array_map(function ($element) {
                return "&middot; $element";
            }, preg_split('/\r\n|\r|\n/', $this->details)),
        ) : null;
    }

    public function grouppedData(): ?array
    {
        if (str_contains($this->description, "\n")) {
            $rawData = preg_split('/\r\n|\r|\n/', $this->description);
        } elseif (str_contains($this->details, "\n")) {
            $rawData = preg_split('/\r\n|\r|\n/', $this->details);
        }

        if (isset($rawData)) {
            $data = [];

            foreach ($rawData as $item) {
                $label = trim(explode(' ', $item, 2)[0] ?? '', ' :');
                $content = ucfirst(explode(' ', $item, 2)[1] ?? '');

                if ($label && $content) {
                    $data[$label] = $content;
                }
            }

            if (! empty($data)) {
                return $data;
            }
        }

        return null;
    }
}
