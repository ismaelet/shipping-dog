<?php

namespace User;

use DBOld;
use DynamicProperties;
use Ecommerce\Design;
use User;
use Ecommerce\Order;

class FeedEntry {

	use DynamicProperties;

	public const INDEX = 'eventId';

	private $typeId;
	private $eventId;
	private $subjectId;
	private $time;

	function __construct($row) {
		$this->typeId = $row['type'];
		$this->eventId = $row['eventId'] ?? null;
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
		return $this->typeId == 1 ? DBOld::find('text_it FROM news WHERE ID = ?', $this->eventId) : null;
	}

	private function getprofile() {
		return in_array($this->typeId, [3, 4]) ? new User($this->subjectId) : null;
	}

	private function getorder() {
		return $this->typeId == 2 ? new Order($this->eventId) : null;
	}

	private function getseller() {
		return $this->typeId == 5 ? $this->design->seller : null;
	}

	private function getdesign() {
		return $this->calcDesign();
	}

	private function calcText() {
		switch ($this->typeId) {
			case 1:
				$text = '<strong><span class="txt-c1">Shipping Dog</span></strong>: ' . $this->news;
				break;

			case 2:
				$text = 'Hai venduto <strong>' . $this->order->quantity . ' x</strong> <a class="design" href="' . $this->order->product->clientUrl . '">' . $this->order->product->name . '</a> per <strong>' . $this->order->gainString . '</strong>';
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
		switch ($this->typeId) {
			case 1:
				return public_asset('/users/icons/0.jpg');
			case 2:
			case 5:
				return $this->order->product->image;
			case 3:
			case 4:
				return $this->profile->icon;
			default:
				return null;
		}
	}

	private function calcDesign() {
		switch ($this->typeId) {
			case 2:
				$design = $this->order->product->design;
				break;
			case 3:
			case 5:
				$design = new Design($this->eventId);
				break;
			default:
				$design = null;
				break;
		}

		return $design;
	}
}
