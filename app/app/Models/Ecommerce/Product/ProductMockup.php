<?php

namespace App\Models\Ecommerce\Product;

use App\Models\Ecommerce\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMockup extends Model {

	protected $table = 'items_previews';
	protected $fillable = ['product_id', 'position_id', 'size', 'pos_x', 'pos_y'];


	public function product(): BelongsTo {
		return $this->belongsTo(Product::class);
	}

	public function position(): BelongsTo {
		return $this->belongsTo(ProductPosition::class);
	}
}
