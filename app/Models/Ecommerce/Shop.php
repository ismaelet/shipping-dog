<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Shop\Follower;
use App\Models\Shop\Price;
use App\Models\Traits\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Shop class
 * 
 * @method earned()
 * @method credit()
 */
class Shop extends Model {

	use Cache;

	protected $primaryKey = 'userID';
	protected $table = 'stores';
	protected $fillable = [];

	/**
	 * Get the shop's designs
	 */
	public function designs(): HasMany {
		return $this->hasMany(Design::class, 'userID', 'userID');
	}

	/**
	 * Get the shop's followers
	 */
	public function followers(): HasMany {
		return $this->hasMany(Follower::class, 'followID', 'userID');
	}

	/**
	 * Get the shop's prices
	 */
	public function prices() {
		return $this->hasMany(Price::class, 'storeID', 'userID');
	}

	/**
	 * Get the shop's logo groups
	 */
	public function logoGroups(): HasMany {
		return $this->hasMany(LogoGroup::class, 'storID', 'userID');
	}

	/**
	 * Get the shop's orders
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class, 'shopId', 'userID');
	}

	/**
	 * Get the shop's total sales
	 */
	public function earned(): float {
		// Return cached value if exists
		if ($this->getCache('earned') !== null) {
			return $this->getCache('earned');
		}

		// Get the total sales
		$count = $this->orders()
			->join('carts', 'orders.cartID', '=', 'carts.ID')
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
	 * Get the shop's credit
	 */
	public function credit(): float {
		// Return cached value if exists
		if ($this->getCache('credit') !== null) {
			return $this->getCache('credit');
		}

		return $this->setCache('credit', round($this->earnedCredit() - $this->usedOrderCredit() - $this->usedShippingCredit(), 2));
	}

	/**
	 * Get the shop's earned credit
	 */
	private function earnedCredit(): float {
		if ($this->getCache('earnedCredit') !== null) {
			return $this->getCache('earnedCredit');
		}

		return $this->setCache('earnedCredit', round($this->earned() * .1, 2));
	}

	/**
	 * Get the shop's used credit to pay orders
	 */
	private function usedOrderCredit(): float {
		if ($this->getCache('usedOrderCredit') !== null) {
			return $this->getCache('usedOrderCredit');
		}

		$usedOrderCredit = $this->orders()
			->where('buyer', '=', $this->userID)
			->where('credit', '=', 1)
			->selectRaw('SUM((fullProductPrice - price_disc) * orders.quantity) as usedCredit')
			->first()->usedCredit;

		return $this->setCache('usedOrderCredit', round($usedOrderCredit, 2));
	}

	/**
	 * Get the shop's used credit to pay shipping
	 */
	private function usedShippingCredit(): float {
		if ($this->getCache('usedShippingCredit') !== null) {
			return $this->getCache('usedShippingCredit');
		}

		$usedShippingCredit = Cart::whereHas('orders', function ($query) {
			$query->where('buyer', '=', $this->userID);
		})
			->where('credit_ship', '=', 1)
			->selectRaw('
			SUM(CASE WHEN quantity = 1 THEN 5.9 ELSE 7.4 END) AS usedCredit
		')
			->first()->usedCredit;

		return $this->setCache('usedShippingCredit', round($usedShippingCredit, 2));
	}

	/**
	 * Check if the shop is followed by the logged user
	 */
	public function isFollowed(): bool {
		return Follower::where([
			'userID' => userId(),
			'followID' => $this->userID,
		])->count() > 0;
	}

	/**
	 * Delete the shop and clean all related data
	 */
	public function deleteAndClean(): void {
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

		// Delete the shop
		$this->delete();
	}

	/**
	 * Get the shops awaiting approval
	 */
	public static function awaitingApproval(): Collection {
		return self::where('status', 0)
			->whereIn('userID', function ($query) {
				$query->select('userID')
					->from('designs')
					->where('status', 1);
			})
			->where('userID', '!=', 2)
			->get();
	}
}
