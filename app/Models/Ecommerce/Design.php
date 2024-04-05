<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\CustomerPhoto;
use App\Models\Ecommerce\Design\Tag;
use App\Models\User\Like;
use Illuminate\Database\Eloquent\Model;

class Design extends Model {

	protected $primaryKey = 'ID';
	protected $table = 'designs';
	protected $fillable = ['status'];

	/**
	 * Get the design's seller
	 */
	public function seller() {
		return $this->belongsTo(Seller::class, 'userID', 'userID');
	}

	/**
	 * Get the design's products
	 */
	public function products() {
		return $this->hasMany(Product::class, 'desID', 'ID');
	}

	/**
	 * Get the design's likes
	 */
	public function likes() {
		return $this->hasMany(Like::class, 'desID', 'ID');
	}

	/**
	 * Get the design's tags
	 */
	public function tags() {
		return $this->hasMany(Tag::class, 'desID', 'ID');
	}

	/**
	 * Get the design's customer photos
	 */
	public function customerPhotos() {
		return $this->hasMany(CustomerPhoto::class, 'desID', 'ID');
	}

	/**
	 * Check if the design is liked by the logged user
	 */
	public function isLiked(): bool {
		return Like::where([
			'userID' => auth()->id(),
			'desID' => $this->ID,
		])->count() > 0;
	}

	/**
	 * Delete the design and clean the related data
	 */
	public function deleteAndClean($permanently = false) {
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
