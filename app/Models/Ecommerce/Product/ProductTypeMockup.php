<?php

namespace App\Models\Ecommerce\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTypeMockup extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'items_mockups';
	protected $fillable = ['typeID', 'positionID', 'pos_x_min', 'pos_x_def', 'pos_y_min', 'pos_y_def', 'siz_x_max', 'siz_x_def', 'siz_y_max'];

	/**
	 * Get mockup by type ID
	 */
	public static function getByTypeId(int $typeId, ?int $positionId = null): self {
		if ($positionId) {
			return self::where('typeID', $typeId)->where('positionID', $positionId)->first();
		} else {
			return self::where('typeID', $typeId)->orderBy('positionID')->first();
		}
	}

	/**
	 * Get the type of the mockup
	 */
	public function type(): BelongsTo {
		return $this->belongsTo(ProductType::class, 'typeID', 'ID');
	}

	/**
	 * Get the clothing image path
	 */
	public function clothingImage(?int $colorId = null, ?int $positionId = null): string {
		$colorId = $colorId ?: $this->type->colorCollection()->first()->ID;
		$positionId = $positionId ?: 1;
		$color = ProductColor::find($colorId);
		$position = ProductPosition::find($positionId);
		$imageSlug = $color->tag . '-' . $position->basicPositionId();

		return '/app/assets/mockups/' . $this->type->tag . '/' . $imageSlug . '.webp';
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
