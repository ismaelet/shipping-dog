<?php

namespace Ecommerce;

use DBOld;
use DatabaseObject;

/**
 * Shipping method
 * 
 * @property int productQuantity
 * 
 * @property int minimumWorkingDays
 * @property int maximumWorkingDays
 * @property int minimumShippingDays
 * @property int maximumShippingDays
 * @property int minimumRequiredDays
 * @property int maximumRequiredDays
 * 
 * @property int minimumDeliveryDate
 * @property int maximumDeliveryDate
 * @property string minimumDeliveryDateString
 * @property string maximumDeliveryDateString
 * @property string deliveryDateString
 */
class ShippingMethod {

	use DatabaseObject;

	public const TABLE = 'sys_shipping';
	public const INDEX = 'id';

	private const REQUIRED_WORKING_DAYS = [1, 2];
	private const WEEKEND_DAYS = [6, 7];
	private const HOLIDAYS = ['25-12', '26-12', '15-08'];
	private const MONTH_NAMES = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
	private const DAY_NAMES = ['Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];

	public const FREE_SHIPPING_METHOD_ID = 1;
	public const PRIMARY_SHIPPING_METHOD_ID = 2;
	public const NO_DELIVERY_SHIPPING_METHOD_ID = 11;

	/**
	 * Get methods
	 */
	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}

	private function getName() {
		return $this->name;
	}

	private function getDescription() {
		return $this->desc;
	}

	private function getAssociatedCart() {
		return cart_id() ? \App\Helpers\App::cartOld() : null;
	}

	private function getProductQuantity() {
		return $this->associatedCart->quantity;
	}

	private function getPrice() {
		$price = 0;

		// fetch shipping data
		$shipping = DBOld::row('* FROM sys_shipping WHERE id = ?', [$this->id]);

		if ($shipping) {
			// calculate price
			$price = $shipping['prc_base'] + $shipping['prc_more'] * ($this->productQuantity - 1);

			// stop at max price
			if ($price > $shipping['prc_max']) $price = $shipping['prc_max'];
		}

		return $price;
	}

	private function getPriceString() {
		return format_price($this->price, false);
	}

	private function getLocalDelivery() {
		return $this->id == self::NO_DELIVERY_SHIPPING_METHOD_ID;
	}

	private function getShipped() {
		return !$this->localDelivery;
	}

	private function getMinimumWorkingDays() {
		return $this->associatedCart->longDeliveryTime ? 7 : ((date('H') > 18) ? 2 : 1);
	}

	private function getMaximumWorkingDays() {
		return $this->associatedCart->longDeliveryTime ? 8 : 5;
	}

	private function getMinimumShippingDays() {
		return $this->id == self::NO_DELIVERY_SHIPPING_METHOD_ID ? 0 : $this->days_min;
	}

	private function getMaximumShippingDays() {
		return $this->id == self::NO_DELIVERY_SHIPPING_METHOD_ID ? 0 : $this->days_max;
	}

	private function getMinimumRequiredDays() {
		return $this->minimumWorkingDays + $this->minimumShippingDays;
	}

	private function getMaximumRequiredDays() {
		return $this->maximumWorkingDays + $this->maximumShippingDays;
	}

	private function getDeliveryDate(bool $maximumDate = false) {
		$daysPassed = 0;
		$workDaysPassed = 0;

		$cart = $this->associatedCart ?: \App\Helpers\App::cartOld();
		$cartTime = $cart->statusId ? $cart->time : date('Y-m-d');

		$weekDayNumber = date('N', strtotime($cartTime));

		$totalRequiredDays = $maximumDate ? $this->maximumRequiredDays : $this->minimumRequiredDays;

		while ($workDaysPassed < $totalRequiredDays) {
			$daysPassed++;
			$weekDayNumber++;

			if ($weekDayNumber > 7) {
				$weekDayNumber = 1;
			}

			$currentDate = date('d-m', strtotime($cartTime . ' +' . $daysPassed . ' days'));

			if (!in_array($weekDayNumber, self::WEEKEND_DAYS) && !in_array($currentDate, self::HOLIDAYS)) {
				$workDaysPassed++;
			}
		}

		return strtotime($cartTime . ' +' . $daysPassed . ' days');
	}

	private function getDateString($date) {
		$weekDayNumber = date('N', $date);
		$dayNumber = date('d', $date);
		$monthNumber = date('m', $date);

		return self::DAY_NAMES[$weekDayNumber - 1] . ' <strong>' . $dayNumber . '</strong> ' . strtolower(substr(self::MONTH_NAMES[$monthNumber - 1], 0, 3)) . '.';
	}

	private function getMinimumDeliveryDate() {
		return $this->getDeliveryDate(false);
	}

	private function getMaximumDeliveryDate() {
		return $this->getDeliveryDate(true);
	}

	private function getMinimumDeliveryDateString() {
		return $this->getDateString($this->minimumDeliveryDate);
	}

	private function getMaximumDeliveryDateString() {
		return $this->getDateString($this->maximumDeliveryDate);
	}

	private function getDeliveryDateString() {
		return $this->minimumDeliveryDateString . ' - ' . strtolower($this->maximumDeliveryDateString);
	}

	/**
	 * Set methods
	 */
	public function setProductQuantity(int $productQuantity) {
		$this->data['productQuantity'] = $productQuantity;

		return $this;
	}

	public function setAssociatedCart(object $cart) {
		$this->data['associatedCart'] = $cart;
		$this->data['productQuantity'] = $cart->quantity;

		return $this;
	}
}
