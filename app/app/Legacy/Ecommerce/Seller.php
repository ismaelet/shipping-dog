<?php

namespace App\Legacy\Ecommerce;

use App\Legacy\System\DB;
use App\Legacy\Traits\DatabaseObject;
use App\Models\Ecommerce\Seller as NewSeller;
use App\Legacy\User as LegacyUser;
use Illuminate\Support\Facades\Auth;

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

    public const TABLE = 'sellers';
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

    public function newSeller() {
        return NewSeller::find($this->id);
    }

    private function getcreatedAtString() {
        return date('m/Y', strtotime($this->time));
    }

    private function getUrl() {
        return route('seller.show', $this->newSeller());
    }

    private function getClientUrl() {
        return public_client_url($this->url);
    }

    private function getFollowed() {
        return Auth::check() && DB::exists('followers WHERE user_id = ? AND follower_id = ?', [Auth::id(), $this->id]);
    }

    private function getUserData() {
        return new LegacyUser($this->id);
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

    private function getPayed() {
        return DB::sum('value FROM seller_payments WHERE seller_id = ? AND other = 0', [$this->id]);
    }

    private function getPayedString() {
        return price($this->payed);
    }

    private function getOtherTransfers() {
        return DB::sum('value FROM seller_payments WHERE seller_id = ? AND other = 1', [$this->id]);
    }

    private function getOtherTransfersString() {
        return price($this->otherTransfers);
    }

    private function getToPay() {
        return $this->earned - $this->payed + $this->otherTransfers;
    }

    private function getToPayString() {
        return price($this->toPay);
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
        DB::delete('seller_payments WHERE seller_id = ?', [$this->id]);

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
