<?php

namespace App\Models\Ecommerce;

use App\Helpers\EcommerceHelper;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model {

	public const FREE_SHIPPING_METHODS = [1];
	public const NO_DELIVERY_SHIPPING_METHODS = [11];
	public const SHIPPING_METHODS_WITH_LABELS = [1, 2];

	private const REQUIRED_WORKING_DAYS = [1, 2];
	private const WEEKEND_DAYS = [6, 7];
	private const HOLIDAYS = ['25-12', '26-12', '15-08'];
	private const MONTH_NAMES = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
	private const DAY_NAMES = ['Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];

	protected $table = 'sys_shipping';
	protected $fillable = [];

	/************************
	 * * Utility methods *
	 ************************/
	/**
	 * Get the shipping price
	 */
	public function price(int $productQuantity): float {
		// calculate price
		$price = $this->prc_base + $this->prc_more * ($productQuantity - 1);

		// stop at max price
		$price = $price > $this->prc_max ? $this->prc_max : $price;

		return $price;
	}

	public function priceString(int $productQuantity): string {
		return EcommerceHelper::formatPrice($this->price($productQuantity));
	}

	/**
	 * Check if the shipping method is local delivery
	 */
	public function isLocalDelivery(): bool {
		return in_array($this->id, self::NO_DELIVERY_SHIPPING_METHODS);
	}

	/**
	 * Check if the shipping method requires shipping
	 */
	public function isShipped(): bool {
		return !$this->isLocalDelivery();
	}

	/**
	 * Estimate the delivery time
	 */
	public function minimumWorkingDays(bool $longDelivery): int {
		return $longDelivery ? 7 : ((date('H') > 18) ? 2 : 1);
	}

	public function maximumWorkingDays(bool $longDelivery): int {
		return $longDelivery ? 8 : 5;
	}

	public function minimumShippingDays(): int {
		return in_array($this->id, self::NO_DELIVERY_SHIPPING_METHODS) ? 0 : $this->days_min;
	}

	public function maximumShippingDays(): int {
		return in_array($this->id, self::NO_DELIVERY_SHIPPING_METHODS) ? 0 : $this->days_max;
	}

	public function minimumRequiredDays(bool $longDelivery): int {
		return $this->minimumWorkingDays($longDelivery) + $this->minimumShippingDays();
	}

	public function maximumRequiredDays(bool $longDelivery): int {
		return $this->maximumWorkingDays($longDelivery) + $this->maximumShippingDays();
	}

	public function deliveryDate(?string $startingTime = null, bool $longDelivery, bool $maximumDate = false): string {
		$daysPassed = 0;
		$workDaysPassed = 0;

		$orderTime = $startingTime ?: date('Y-m-d');

		$weekDayNumber = date('N', strtotime($orderTime));

		$totalRequiredDays = $maximumDate ? $this->maximumRequiredDays($longDelivery) : $this->minimumRequiredDays($longDelivery);

		while ($workDaysPassed < $totalRequiredDays) {
			$daysPassed++;
			$weekDayNumber++;

			if ($weekDayNumber > 7) {
				$weekDayNumber = 1;
			}

			$currentDate = date('d-m', strtotime($orderTime . ' +' . $daysPassed . ' days'));

			if (!in_array($weekDayNumber, self::WEEKEND_DAYS) && !in_array($currentDate, self::HOLIDAYS)) {
				$workDaysPassed++;
			}
		}

		return strtotime($orderTime . ' +' . $daysPassed . ' days');
	}

	public function dateString($date): string {
		$weekDayNumber = date('N', $date);
		$dayNumber = date('d', $date);
		$monthNumber = date('m', $date);

		return self::DAY_NAMES[$weekDayNumber - 1] . ' <span class="font-bold">' . $dayNumber . '</span> ' . strtolower(substr(self::MONTH_NAMES[$monthNumber - 1], 0, 3)) . '.';
	}

	public function minimumDeliveryDate(?string $startingTime = null, bool $longDelivery) {
		return $this->deliveryDate($startingTime, $longDelivery, false);
	}

	public function maximumDeliveryDate(?string $startingTime = null, bool $longDelivery) {
		return $this->deliveryDate($startingTime, $longDelivery, true);
	}

	public function minimumDeliveryDateString(?string $startingTime = null, bool $longDelivery) {
		return $this->dateString($this->minimumDeliveryDate($startingTime, $longDelivery));
	}

	public function maximumDeliveryDateString(?string $startingTime = null, bool $longDelivery) {
		return $this->dateString($this->maximumDeliveryDate($startingTime, $longDelivery));
	}

	public function deliveryDateString(?string $startingTime = null, bool $longDelivery): string {
		return $this->minimumDeliveryDateString($startingTime, $longDelivery) . ' - ' . strtolower($this->maximumDeliveryDateString($startingTime, $longDelivery));
	}

	// * Static methods * //
	/**
	 * Get the shipping methods that require labels
	 */
	public static function shippingMethodsWithLabels(): array {
		return self::SHIPPING_METHODS_WITH_LABELS;
	}
}
