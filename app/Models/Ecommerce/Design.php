<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\CustomerPhoto;
use App\Models\Ecommerce\Design\Tag;
use App\Models\User\Like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Design extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'designs';
	protected $fillable = ['status'];

	/**
	 * Get the design's seller
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class, 'userID', 'userID');
	}

	/**
	 * Get the design's main
	 */
	public function mainProduct(): HasOne {
		return $this->hasOne(Product::class, 'desID', 'ID')
			->where('status', 1)
			->where('deleted', 0)
			->orderBy('typeID', 'asc');
	}

	/**
	 * Get the design's products
	 */
	public function products(): HasMany {
		return $this->hasMany(Product::class, 'desID', 'ID')
			->where('status', 1)
			->where('deleted', 0)
			->orderBy('typeID', 'asc');
	}

	/**
	 * Get the design's likes
	 */
	public function likes(): HasMany {
		return $this->hasMany(Like::class, 'desID', 'ID');
	}

	/**
	 * Get the design's tags
	 */
	public function tags(): HasMany {
		return $this->hasMany(Tag::class, 'desID', 'ID');
	}

	/**
	 * Get the design's customer photos
	 */
	public function customerPhotos(): HasMany {
		return $this->hasMany(CustomerPhoto::class, 'desID', 'ID');
	}

	/**
	 * Ge the desing's url, that is the url of the main product
	 */
	public function url(): string {
		return $this->mainProduct->url();
	}

	/**
	 * Get the count of products
	 */
	public function productCount(): int {
		return $this->products()->count();
	}

	/**
	 * Check if the design is liked by the logged user
	 */
	public function isLiked(): bool {
		return Like::where([
			'userID' => auth()->id(),
			'desID' => $this->ID,
		])->exists();
	}

	/**
	 * Get the design's image
	 */
	public function image(int $size = 400): string {
		$file = $size == 400 ? 'designs/previews/' . $this->ID . '_m.webp' : 'designs/previews/' . $this->ID . '.webp';

		if (!file_exists(public_storage_path($file))) {
			des_img_gen($this->ID);
		}

		if (file_exists(public_storage_path($file))) {
			return public_asset($file);
		}

		return public_asset('product_types/mockups/no-image.webp');
	}

	/**
	 * Delete the design and clean the related data
	 */
	public function deleteAndClean(bool $permanently = false): void {
		// Delete products
		foreach ($this->products as $product) {
			$product->deleteAndClean($permanently);
		}

		if ($permanently) {
			// Delete likes
			foreach ($this->likes as $like) {
				$like->delete();
			}

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
