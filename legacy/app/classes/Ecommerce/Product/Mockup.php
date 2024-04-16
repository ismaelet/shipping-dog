<?php

namespace Ecommerce\Product;

class Mockup {

	private $data = [];

	public $type_id;
	public $colorId;
	public $position_id;

	function __construct($type_id, $colorId, $position_id) {
		$this->type_id = $type_id;
		$this->colorId = $colorId;
		$this->position_id = $position_id;
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
					return $this->data['type'] = new Type($this->type_id);
				case 'color':
					return $this->data['color'] = new Color($this->colorId);
				case 'position':
					return $this->data['position'] = new Position($this->position_id);
			}
		}
	}

	private function getBackgroundImage() {
		$image = '/uploads/product_types/mockups/' . $this->type->slug . '/' . $this->color->slug . '-' . $this->position_id . '.webp';

		return $image;
	}

	private function getFrontImage() {
		$image = '/uploads/product_types/mockups/' . $this->type->slug . '/' . $this->color->slug . '-' . $this->position_id . '-det.webp';

		return $image;
	}
}
