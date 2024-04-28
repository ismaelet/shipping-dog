<?php

namespace Legacy\Classes\User;

use App\Models\Ecommerce\Order as EcommerceOrder;
use Legacy\Classes\Ecommerce\Design;
use Legacy\Classes\User;

class FeedEntry {

	use \Legacy\System\Traits\DynamicProperties;

	public const INDEX = 'event_id';

	private $type_id;
	private $event_id;
	private $subjectId;
	private $time;

	function __construct($row) {
		$this->type_id = $row['type'];
		$this->event_id = $row['event_id'] ?? null;
		$this->subjectId = $row['srcID'] ?? null;
		$this->time = $row['time'] ?? null;
	}

	private function gettimeString() {
		return date('d-m-Y', strtotime($this->time));
	}

	private function gettext() {
		return $this->calcText();
	}

	private function getimage() {
		return $this->calcImg();
	}

	private function getclientImage() {
		return $this->image;
	}

	private function getnews() {
		return $this->type_id == 1 ? \Legacy\System\Classes\LegacyDB::find('text FROM news WHERE id = ?', $this->event_id) : null;
	}

	private function getprofile() {
		return in_array($this->type_id, [3, 4]) ? new User($this->subjectId) : null;
	}

	private function getorder() {
		return $this->type_id == 2 ? EcommerceOrder::find($this->event_id) : null;
	}

	private function getseller() {
		return $this->type_id == 5 ? $this->design->seller : null;
	}

	private function getdesign() {
		return $this->calcDesign();
	}

	private function calcText() {
		switch ($this->type_id) {
			case 1:
				$text = '<strong><span class="txt-c1">Shipping Dog</span></strong>: ' . $this->news;
				break;

			case 2:
				$text = 'Hai venduto <strong>' . $this->order->quantity . ' x</strong> <a class="design" href="' . $this->order->product->url() . '">' . $this->order->product->name . '</a> per <strong>' . $this->order->gainString . '</strong>';
				break;

			case 3:
				$text = '<span class="user">' . $this->profile->username . '</span> ha aggiunto <a class="design" href="' . $this->design->clientUrl . '">' . $this->design->name . '</a> ai preferiti';
				break;

			case 4:
				$text = '<span class="user">' . $this->profile->username . '</span> ha iniziato a seguirti';
				break;

			case 5:
				$text = '<a class="user" href="' . $this->seller->clientUrl . '">' . $this->seller->name . '</a> ha caricato un nuovo design: <a class="design" href="' . $this->design->clientUrl . '">' . $this->design->name . '</a>';
				break;

			default:
				$text = '';
		}

		$text = '<strong>' . $this->timeString . ':</strong> ' . $text;

		return $text;
	}

	private function calcImg() {
		switch ($this->type_id) {
			case 1:
				return public_asset('users/icons/0.jpg');
			case 2:
			case 5:
				return $this->order->image();
			case 3:
			case 4:
				return $this->profile->icon();
			default:
				return null;
		}
	}

	private function calcDesign() {
		switch ($this->type_id) {
			case 2:
				$design = $this->order->product->design;
				break;
			case 3:
			case 5:
				$design = new Design($this->event_id);
				break;
			default:
				$design = null;
				break;
		}

		return $design;
	}
}
