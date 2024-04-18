<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTypeMockup extends Model {

	protected $table = 'items_mockups';
	protected $fillable = ['type_id', 'position_id', 'pos_x_min', 'pos_x_def', 'pos_y_min', 'pos_y_def', 'siz_x_max', 'siz_x_def', 'siz_y_max'];

	/**
	 * Get mockup by type ID
	 */
	public static function firstByTypeId(int $type_id, ?int $position_id = null): self {
		if ($position_id) {
			return self::where('type_id', $type_id)->where('position_id', $position_id)->first();
		} else {
			return self::where('type_id', $type_id)->orderBy('position_id')->first();
		}
	}

	/**
	 * Get the type of the mockup
	 */
	public function type(): BelongsTo {
		return $this->belongsTo(ProductType::class, 'type_id', 'id');
	}

	/**
	 * Get the clothing image path
	 */
	public function clothingImage(?int $colorId = null, ?int $position_id = null): string {
		$colorId = $colorId ?: $this->type->colorCollection()->first()->id;
		$position_id = $position_id ?: 1;
		$color = ProductColor::find($colorId);
		$position = ProductPosition::find($position_id);
		$imageSlug = $color->tag . '-' . $position->basicPositionId();

		return '/uploads/product_types/mockups/' . $this->type->tag . '/' . $imageSlug . '.webp';
	}

	/**
	 * Get the overlay image path
	 */
	public function overlayImage(): ?string {
		$file = str_replace('.', '-det.', $this->clothingImage());
		$serverFile = public_server_path($file);

		return file_exists($serverFile) ? $file : null;
	}
}
