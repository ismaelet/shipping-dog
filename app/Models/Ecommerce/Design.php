<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\CustomerPhoto;
use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Ecommerce\Design\Tag;
use App\Models\User\Like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Design extends Model {

	protected $table = 'designs';
	protected $fillable = ['id', 'name', 'user_id', 'status', 'ratio'];

	/**
	 * Get the design's seller
	 */
	public function seller(): BelongsTo {
		return $this->belongsTo(Seller::class, 'user_id', 'user_id');
	}

	/**
	 * Get the design's main
	 */
	public function mainProduct(): HasOne {
		return $this->hasOne(Product::class)
			->where('status', 1)
			->where('deleted', 0)
			->orderBy('type_id');
	}

	/**
	 * Get the design's products
	 */
	public function products(): HasMany {
		return $this->hasMany(Product::class)
			->where('status', 1)
			->where('deleted', 0)
			->orderBy('type_id', 'asc');
	}

	/**
	 * Get the design's likes
	 */
	public function likes(): HasMany {
		return $this->hasMany(Like::class);
	}

	/**
	 * Get the design's tags
	 */
	public function tags(): HasMany {
		return $this->hasMany(Tag::class);
	}

	/**
	 * Get the design's customer photos
	 */
	public function customerPhotos(): HasMany {
		return $this->hasMany(CustomerPhoto::class);
	}

	/**
	 * Get the design's logo group
	 */
	public function logo_group(): BelongsTo {
		return $this->belongsTo(LogoGroup::class);
	}

	/**
	 * Ge the desing's url, that is the url of the main product
	 */
	public function url(): ?string {
		return $this->mainProduct ? $this->mainProduct->url() : null;
	}

	/**
	 * Get the design's time string
	 */
	public function timeString(): string {
		return date('d/m/Y', strtotime($this->created_at));
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
	public function auth_user_like(): HasOne {
		return $this->hasOne(Like::class)
			->where('user_id', auth()->id());
	}

	public function isLiked(): bool {
		return $this->auth_user_like ? true : false;
	}

	/**
	 * Get the design's image
	 */
	public function image(int $size = 400): string {
		$file = $size == 400 ? 'designs/previews/' . $this->id . '_m.webp' : 'designs/previews/' . $this->id . '.webp';

		if (!file_exists(public_storage($file))) {
			des_img_gen($this->id);
		}

		if (file_exists(public_storage($file))) {
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
