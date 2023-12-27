<?php

namespace App\Models\Ecommerce;

use App\Models\Seller\Follower;
use App\Models\Traits\ModelCache;
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

	protected $primaryKey = 'userID';
	protected $table = 'stores';
	protected $fillable = [];

	/**
	 * Get the seller's orders
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class, 'sellerId', 'userID');
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
			->where('buyer', '=', $this->userID)
			->where('credit', '=', 1)
			->selectRaw('SUM((fullProductPrice - price_disc) * orders.quantity) as usedCredit')
			->first()->usedCredit;

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
			$query->where('buyer', '=', $this->userID);
		})
			->where('credit_ship', '=', 1)
			->selectRaw('
			SUM(CASE WHEN quantity = 1 THEN 5.9 ELSE 7.4 END) AS usedCredit
		')
			->first()->usedCredit;

		return $this->setCache('usedShippingCredit', round($usedShippingCredit, 2));
	}

	public function isFollowed(): bool {
		return Follower::where([
			'userID' => userId(),
			'followID' => $this->userID,
		])->count() > 0;
	}
}
