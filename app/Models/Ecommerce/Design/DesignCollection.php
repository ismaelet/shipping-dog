<?php

namespace App\Models\Ecommerce\Design;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DesignCollection extends Model {

	protected $table = 'collections';
	protected $fillable = [];

	/*
	 * * Relationship instances
	 */
	// Get the collection's seller
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class, 'user_id');
	}

	// Get the collection's main design
	public function mainDesign(): HasOne {
		return $this->hasOne(Design::class, 'collection', 'id')->oldest();
	}

	/*
	 * * Utility methods
	 */
	// Get the collection's URL
	public function url(): string {
		return '/artist/collection?id=' . $this->id;
	}

	// Get the collection's image
	public function image(int $size = 400): ?string {
		$image = $size == 400 ? '/shops/collections/' . $this->id . '_m.jpg' : '/shops/collections/' . $this->id . '.jpg';

		if (file_exists(public_storage($image))) {
			return $image;
		}

		return null;
	}
}
