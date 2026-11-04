<?php

namespace App\Models\Ecommerce\Product\Type;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items_types_measures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'type_id',
        'size',
        'width',
        'height',
    ];

    /**
     * The name accessor for the measure.
     *
     * @return Attribute The position ID attribute.
     */
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn (): string => in_array($this->size, ['xs', 's', 'm', 'l', 'xl', 'xxl', '2xl', '3xl']) ? strtoupper($this->size) : $this->size,
        );
    }

    /**
     * The measures to be displayed accessor.
     *
     * @return Attribute The position ID attribute.
     */
    public function displayMeasure(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $measures = [$this->width, $this->height];
                $measures = array_map(function ($measure) {
                    return $measure.'cm';
                }, $measures);

                return implode(' / ', $measures);
            },
        );
    }
}
