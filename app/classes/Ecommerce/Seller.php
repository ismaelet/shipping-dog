<?php

namespace Ecommerce;

use DB;
use DatabaseObject;
use User;

/**
 * Seller class
 * 
 * @property float earned
 * @property float toPay
 * @property float payed
 * @property float otherTransfers
 */
class Seller {

	use DatabaseObject;

	public const TABLE = 'stores';
	public const INDEX = 'userID';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}
	private function getIsCustom() {
		return $this->custom;
	}

	private function getTimeString() {
		return date('m/Y', strtotime($this->time));
	}

	private function getUrl() {
		return '/artist?id=' . $this->id;
	}

	private function getClientUrl() {
		return public_client_url($this->url);
	}

	private function getFollowed() {
		return logged() && DB::exists('followers WHERE userID = ? AND followID = ?', [userId(), $this->id]);
	}

	private function getUserData() {
		return new User($this->id);
	}

	private function getIcon() {
		return $this->userData->icon;
	}

	private function getCover() {
		return $this->userData->cover;
	}

	private function getHasCover() {
		return (bool) !strpos($this->userData->cover[0], '/0.jpg');
	}

	private function getEarned(): float {
		$earned = DB::row(
			'
		SUM((' . Order::PRODUCT_GAIN_FIELD . ' - gain_disc) * orders.quantity) as totGain
		FROM orders INNER JOIN carts ON orders.cartID = carts.ID
		WHERE sellerId = ? AND carts.status > 0',
			[$this->id]
		)['totGain'];

		if ($earned !== null) {
			return round($earned, 2);
		} else {
			return 0;
		}
	}

	private function getEarnedString() {
		return format_price($this->earned);
	}

	private function getPayed() {
		return DB::sum('value FROM stores_payments WHERE storeID = ? AND other = 0', [$this->id]);
	}

	private function getPayedString() {
		return format_price($this->payed);
	}

	private function getOtherTransfers() {
		return DB::sum('value FROM stores_payments WHERE storeID = ? AND other = 1', [$this->id]);
	}

	private function getOtherTransfersString() {
		return format_price($this->otherTransfers);
	}

	private function getToPay() {
		return $this->earned - $this->payed + $this->otherTransfers;
	}

	private function getToPayString() {
		return format_price($this->toPay);
	}

	private function getCredit() {
		return DB::find('credit FROM stores WHERE userID = ?', [$this->id]);
	}

	private function getCreditString() {
		return format_price($this->credit);
	}

	private function getDesigns() {
		return rows_to_objects(DB::select(Design::INDEX . ' FROM ' . Design::TABLE . ' WHERE ' . Design::SELLER_INDEX . ' = ?', [$this->id]), Design::class);
	}

	private function getProducts() {
		return rows_to_objects(DB::select(Product::INDEX . ' FROM ' . Product::TABLE . ' WHERE ' . Product::SELLER_INDEX . ' = ?', [$this->id]), Product::class);
	}

	private function getStatusId() {
		return $this->status;
	}

	/*
	* Set methods
	*/
	public function setStatusId(int $statusId) {
		$this->data['statusId'] = $statusId;

		return $this;
	}

	/*
	* Update methods
	*/
	public function updateStatusId(int $statusId) {
		DB::update(self::TABLE . ' SET ' . ' status = ? WHERE ' . self::INDEX . ' = ?', [$statusId, $this->id]);

		$this->setStatusId($statusId);
	}

	/*
	* Instance methods
	*/
	public function delete() {
		DB::delete(self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
		DB::delete('stores_payments WHERE storeID = ?', [$this->id]);

		foreach ($this->designs as $design) {
			$design->delete(true);
		}

		foreach ($this->products as $product) {
			$product->delete(true);
		}

		$this->reset();

		return $this;
	}

	/*
	* Render methods
	*/

	/*
	* Misc methods
	*/
	public function accept() {
		$this->updateStatusId(1);
	}
}
