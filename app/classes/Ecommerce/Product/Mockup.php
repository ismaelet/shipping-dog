<?php

namespace Ecommerce\Product;

class Mockup {

	private $data = [];

	public $typeId;
	public $colorId;
	public $positionId;

	function __construct($typeId, $colorId, $positionId) {
		$this->typeId = $typeId;
		$this->colorId = $colorId;
		$this->positionId = $positionId;
	}

	function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		} else {
			switch ($name) {
				case 'nameWithoutType':
					return $this->data['nameWithoutType'] = $this->color->name . ' - ' . $this->position->name;
				case 'backgroundImage':
					return $this->data['backgroundImage'] = $this->getBackgroundImage();
				case 'frontImage':
					return $this->data['frontImage'] = $this->getFrontImage();
				case 'hasBackgroundImage':
					return $this->data['hasBackgroundImage'] = file_exists(public_server_path($this->getBackgroundImage()));
				case 'hasFrontImage':
					return $this->data['hasFrontImage'] = file_exists(public_server_path($this->getFrontImage()));

					// related classes
				case 'type':
					return $this->data['type'] = new Type($this->typeId);
				case 'color':
					return $this->data['color'] = new Color($this->colorId);
				case 'position':
					return $this->data['position'] = new Position($this->positionId);
			}
		}
	}

	private function getBackgroundImage() {
		$image = '/app/assets/mockups/' . $this->type->slug . '/' . $this->color->slug . '-' . $this->positionId . '.webp';

		return $image;
	}

	private function getFrontImage() {
		$image = '/app/assets/mockups/' . $this->type->slug . '/' . $this->color->slug . '-' . $this->positionId . '-det.webp';

		return $image;
	}
}
