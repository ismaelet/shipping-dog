<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Ecommerce\Seller\Follower;
use App\Models\Ecommerce\Seller\SellerPrice;
use App\Models\Traits\ModelCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Seller class
 * 
 * @method earned()
 * @method credit()
 */
class Seller extends Model {

	use ModelCache;

	protected $primaryKey = 'user_id';
	protected $table = 'stores';
	protected $fillable = [];

	/**
	 * Get the seller's designs
	 */
	public function designs(): HasMany {
		return $this->hasMany(Design::class, 'user_id', 'user_id');
	}

	/**
	 * Get the seller's followers
	 */
	public function followers(): HasMany {
		return $this->hasMany(Follower::class, 'follower_id', 'user_id');
	}

	/**
	 * Get the seller's prices
	 */
	public function prices() {
		return $this->hasMany(SellerPrice::class, 'seller_id', 'user_id');
	}

	/**
	 * Get the seller's logo groups
	 */
	public function logoGroups(): HasMany {
		return $this->hasMany(LogoGroup::class, 'seller_id', 'user_id');
	}

	/**
	 * Get the formatted time string
	 */
	public function timeString(): string {
		return date('d/m/Y', strtotime($this->time));
	}

	/**
	 * Get the seller's orders
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class, 'seller_id', 'user_id');
	}

	/**
	 * Get the seller's URL
	 */
	public function url(): string {
		return '/artist?id=' . $this->user_id;
	}

	/**
	 * Get the seller's total sales
	 */
	public function earned(): float {
		// Return cached value if exists
		if ($this->getCache('earned') !== null) {
			return $this->getCache('earned');
		}

		// Get the total sales
		$count = $this->orders()
			->join('carts', 'orders.cart_id', '=', 'carts.id')
			->where('carts.status', '>', 0)
			->selectRaw('SUM((productGain - gain_disc) * orders.quantity) as totalGain')
			->first();

		// Round the value to 2 decimals
		$earned = $count->totalGain;

		if ($earned !== null) {
			return $this->setCache('earned', round($earned, 2));
		} else {
			return $this->setCache('earned', 0);
		}
	}

	/**
	 * Get the seller's credit
	 */
	public function credit() {
		// Return cached value if exists
		if ($this->getCache('credit') !== null) {
			return $this->getCache('credit');
		}

		return $this->setCache('credit', round($this->earnedCredit() - $this->usedOrderCredit() - $this->usedShippingCredit(), 2));
	}

	/**
	 * Get the seller's earned credit
	 */
	private function earnedCredit(): float {
		if ($this->getCache('earnedCredit') !== null) {
			return $this->getCache('earnedCredit');
		}

		return $this->setCache('earnedCredit', round($this->earned() * .1, 2));
	}

	/**
	 * Get the seller's used credit to pay orders
	 */
	private function usedOrderCredit(): float {
		if ($this->getCache('usedOrderCredit') !== null) {
			return $this->getCache('usedOrderCredit');
		}

		$usedOrderCredit = $this->orders()
			->where('user_id', '=', $this->user_id)
			->where('credit', '=', 1)
			->selectRaw('SUM((fullProductPrice - price_disc) * orders.quantity) as usedCredit')
			->first()->usedCredit ?: 0;

		return $this->setCache('usedOrderCredit', round($usedOrderCredit, 2));
	}

	/**
	 * Get the seller's used credit to pay shipping
	 */
	private function usedShippingCredit(): float {
		if ($this->getCache('usedShippingCredit') !== null) {
			return $this->getCache('usedShippingCredit');
		}

		$usedShippingCredit = Cart::whereHas('orders', function ($query) {
			$query->where('user_id', '=', $this->user_id);
		})
			->where('credit_ship', '=', 1)
			->selectRaw('
			SUM(CASE WHEN quantity = 1 THEN 5.9 ELSE 7.4 END) AS usedCredit
		')
			->first()->usedCredit ?: 0;

		return $this->setCache('usedShippingCredit', round($usedShippingCredit, 2));
	}

	/**
	 * Check if the seller is followed by the logged user
	 */
	public function isFollowed(): bool {
		return Follower::where([
			'user_id' => auth()->id(),
			'follower_id' => $this->user_id,
		])->count() > 0;
	}

	/**
	 * Get the seller's icon
	 */
	public function icon(): string {
		$icon = 'users/icons/' . $this->user_id;

		if (file_exists(public_storage($icon . '.webp'))) {
			return public_asset($icon . '.webp');
		}

		if (file_exists(public_storage($icon . '.jpg'))) {
			return public_asset($icon . '.jpg');
		}

		return public_asset('users/icons/0.jpg');
	}

	/**
	 * Get the shops awaiting approval
	 */
	public static function awaitingApproval(): Collection {
		return self::where('status', 0)
			->where('user_id', '!=', 2)
			->whereIn('user_id', function ($query) {
				$query->select('user_id')
					->from('designs')
					->where('status', 1);
			})
			->get();
	}

	/**
	 * Delete the seller and clean up the related data
	 */
	public function deleteAndClean() {
		// Delete designs
		foreach ($this->designs as $design) {
			$design->deleteAndClean(true);
		}

		// Delete logo groups
		foreach ($this->logoGroups as $logoGroup) {
			$logoGroup->deleteAndClean();
		}

		// Delete followers
		$this->followers()->delete();

		// Delete prices
		$this->prices()->delete();

		// Delete the seller
		$this->delete();
	}
}
