<?php

namespace Legacy\Classes\Ecommerce;

use App\Models\Ecommerce\Seller as EcommerceSeller;
use Legacy\Classes\User;

/**
 * Seller class
 * 
 * @property float earned
 * @property float toPay
 * @property float payed
 * @property float otherTransfers
 */
class Seller {

	use \Legacy\System\Traits\DatabaseObject;

	public const TABLE = 'stores';
	public const INDEX = 'user_id';

	/*
	* Get methods
	*/
	private function getId() {
		return $this->databaseEntry[self::INDEX];
	}
	private function getIsCustom() {
		return $this->custom;
	}

	private function getcreatedAtString() {
		return date('m/Y', strtotime($this->time));
	}

	private function getUrl() {
		return '/artist?id=' . $this->id;
	}

	private function getClientUrl() {
		return public_client_url($this->url);
	}

	private function getFollowed() {
		return auth()->check() && \Legacy\System\Classes\LegacyDB::exists('followers WHERE user_id = ? AND follower_id = ?', [auth()->id(), $this->id]);
	}

	private function getUserData() {
		return new User($this->id);
	}

	private function getIcon() {
		return $this->userData->icon;
	}

	public function cover(int $size = 400) {
		return $this->userData->cover($size);
	}

	private function getCover() {
		return $this->userData->cover;
	}

	private function getHasCover() {
		return (bool) !strpos($this->userData->cover[0], '/0.jpg');
	}

	private function getEarned(): float {
		$earned = \Legacy\System\Classes\LegacyDB::row(
			'
		SUM((' . OrderItem::PRODUCT_GAIN_FIELD . ' - gain_disc) * order_items.quantity) as totGain
		FROM order_items INNER JOIN orders ON order_items.order_id = orders.id
		WHERE seller_id = ? AND orders.status > 0',
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
		return \Legacy\System\Classes\LegacyDB::sum('value FROM stores_payments WHERE seller_id = ? AND other = 0', [$this->id]);
	}

	private function getPayedString() {
		return format_price($this->payed);
	}

	private function getOtherTransfers() {
		return \Legacy\System\Classes\LegacyDB::sum('value FROM stores_payments WHERE seller_id = ? AND other = 1', [$this->id]);
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
		return EcommerceSeller::find($this->id)->credit();
	}

	private function getCreditString() {
		return format_price($this->credit);
	}

	private function getDesigns() {
		return rows_to_objects(\Legacy\System\Classes\LegacyDB::select(Design::INDEX . ' FROM ' . Design::TABLE . ' WHERE ' . Design::SELLER_INDEX . ' = ?', [$this->id]), Design::class);
	}

	private function getProducts() {
		return rows_to_objects(\Legacy\System\Classes\LegacyDB::select(Product::INDEX . ' FROM ' . Product::TABLE . ' WHERE ' . Product::SELLER_INDEX . ' = ?', [$this->id]), Product::class);
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
		\Legacy\System\Classes\LegacyDB::update(self::TABLE . ' SET ' . ' status = ? WHERE ' . self::INDEX . ' = ?', [$statusId, $this->id]);

		$this->setStatusId($statusId);
	}

	/*
	* Instance methods
	*/
	public function delete() {
		\Legacy\System\Classes\LegacyDB::delete(self::TABLE . ' WHERE ' . self::INDEX . ' = ?', [$this->id]);
		\Legacy\System\Classes\LegacyDB::delete('stores_payments WHERE seller_id = ?', [$this->id]);

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
