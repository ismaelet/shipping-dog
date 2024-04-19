<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Ecommerce\Seller\Follower;
use App\Models\Ecommerce\Seller\SellerPrice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Seller class
 */
class Seller extends Model {

	protected $table = 'stores';
	protected $fillable = ['credit', 'earned'];

	/***************************
	 * * Relationship instances *
	 ***************************/
	/**
	 * Get the seller's designs
	 */
	public function designs(): HasMany {
		return $this->hasMany(Design::class, 'user_id', 'user_id');
	}

	/**
	 * Get the seller's orders
	 */
	public function orders(): HasMany {
		return $this->hasMany(Order::class, 'seller_id', 'user_id');
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
	public function prices(): HasMany {
		return $this->hasMany(SellerPrice::class, 'seller_id', 'user_id');
	}

	/**
	 * Get the seller's logo groups
	 */
	public function logoGroups(): HasMany {
		return $this->hasMany(LogoGroup::class, 'seller_id', 'user_id');
	}
	/************************
	 * * Utility methods *
	 ************************/
	/**
	 * Get the formatted time string
	 */
	public function timeString(): string {
		return date('d/m/Y', strtotime($this->time));
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
		// Get the total sales
		$count = $this->orders()
			->where('status', '>', 0)
			->selectRaw('SUM((productGain - gain_disc) * quantity) as totalGain')
			->first();

		// Round the value to 2 decimals
		$earned = $count->totalGain;

		if ($earned !== null) {
			return round($earned, 2);
		}

		return 0;
	}

	/**
	 * Get the seller's credit
	 */
	public function credit(): float {
		return round($this->earnedCredit() - $this->usedOrderCredit() - $this->usedShippingCredit(), 2);
	}

	private function earnedCredit(): float {
		return round($this->earned() * .1, 2);
	}

	private function usedOrderCredit(): float {
		$usedOrderCredit = $this->orders()
			->where('user_id', '=', $this->user_id)
			->where('credit', '=', 1)
			->selectRaw('SUM((fullProductPrice - price_disc) * quantity) as usedCredit')
			->value('usedCredit');

		return $usedOrderCredit ? round($usedOrderCredit, 2) : 0;
	}

	private function usedShippingCredit(): float {
		$usedShippingCredit = Cart::whereHas('orders', function ($query) {
			$query->where('user_id', '=', $this->user_id);
		})
			->where('credit_ship', '=', 1)
			->selectRaw('
			SUM(CASE WHEN quantity = 1 THEN 5.9 ELSE 7.4 END) AS usedCredit
		')
			->value('usedCredit');

		return $usedShippingCredit ? round($usedShippingCredit, 2) : 0;
	}

	/**
	 * Check if the seller is followed by the logged user
	 */
	public function auth_follower(): HasOne {
		return $this->hasOne(Follower::class, 'user_id', 'id')
			->where('follower_id', auth()->id());
	}

	public function isFollowed(): bool {
		return $this->auth_follower()->exists();
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

	// * Static methods * //
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
}
