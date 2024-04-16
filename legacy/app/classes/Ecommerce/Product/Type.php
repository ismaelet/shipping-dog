<?php

namespace Ecommerce\Product;

use DBOld;
use Ecommerce\Product;

class Type {

	public const TABLE = 'items_types';
	public const INDEX = 'id';
	public const GENDER_INDEX = 'genderID';

	private $data = [];

	public $id = null;
	public $slug;
	public $name;
	public $names;
	public $availableColorSlugs;
	public $sizes;
	public $genderId;
	public $unisex;
	public $position_ids;
	public $category;
	public $class;
	public $custom;
	public $sibling;
	public $blank_product_id;
	public $preorder;
	public $availablePositionIds;

	function __construct($id) {
		if ($row = DBOld::row('* FROM items_types WHERE id = ?', [$id])) {
			$this->id = $id;
			$this->slug = $row['tag'];
			$this->name = $row['name'];
			$this->names = $row['names'];
			$this->availableColorSlugs = empty($row['colors']) ? ['emp'] : explode(',', $row['colors']);
			$this->sizes = explode(',', $row['sizes']);
			$this->genderId = $row['gender'];
			$this->unisex = $row['unisex'];
			$this->sibling = $row['sibling'];
			$this->category = $row['category'];
			$this->availablePositionIds = explode(',', $row['positions']);
			$this->custom = $row['custom'];
			$this->blank_product_id = $row['blank_product_id'];
			$this->preorder = $row['preorder'];

			$this->class = $this->unisex ? substr($this->slug, 0, 3) : $this->slug;
		}
	}

	function __get($name) {
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		} else {
			switch ($name) {
					// related classes
				case 'gender':
					return $this->data['gender'] = new Gender($this->genderId);
				case 'blankProduct':
					return $this->data['blankProduct'] = new Product($this->blank_product_id);

					// other data
				case 'fullName':
					return $this->data['fullName'] = $this->genderId != 3 ? $this->name . ' ' . $this->gender->name : $this->name;
				case 'basicPositionId':
					return $this->data['basicPositionId'] = $this->availablePositionIds[0];
				case 'blankImage':
					return $this->data['blankImage'] = $this->getBlankImage();
				case 'colors':
					return $this->data['colors'] = $this->getColors();
				case 'publicColors':
					return $this->data['publicColors'] = $this->getColors(false);
				case 'positions':
					return $this->data['positions'] = $this->getPositions();
			}
		}
	}

	private function getBlankImage() {
		$image = '/uploads/product_types/mockups/' . $this->slug . '.webp';

		if (!file_exists(SERVER_ROOT . $image)) {
			$image = '/uploads/product_types/mockups/' . $this->slug . '.jpg';
		}

		return $image;
	}

	private function getColors(bool $all = true) {
		$colors = [];

		foreach ($this->availableColorSlugs as $colorSlug) {
			$colorId = $all ?
				DBOld::find('id FROM items_colors WHERE tag = ?', [$colorSlug]) :
				DBOld::find('id FROM items_colors WHERE tag = ? AND hidden = 0', [$colorSlug]);

			if ($colorId) {
				$colors[] = new Color($colorId);
			}
		}

		return $colors;
	}

	private function getPositions() {
		$positions = [];

		foreach ($this->availablePositionIds as $position_id) {
			$position = new Position($position_id);

			if ($position->id) {
				$positions[] = $position;
			}
		}

		return $positions;
	}

	public function getImage(int $colorId, $position_id = null) {
		$color = new Color($colorId);
		$position_id = $position_id ?: $this->basicPositionId;
		$position = new Position($position_id);
		$imageSlug = $color->slug . '-' . $position->imagePositionId;
		$image = $this->data['images'][$imageSlug] ?? null;

		if (!$image) {
			switch (true) {
				case (file_exists(server_root() . $file = '/uploads/product_types/mockups/' . $this->slug . '/' . $color->slug . '-' . $position->imagePositionId . '.webp')):
					$image = $file;
					break;
				case (file_exists(server_root() . $file = '/uploads/product_types/mockups/' . $this->slug . '/' . $color->slug . '-' . $position->imagePositionId . '.png')):
					$image = $file;
					break;
				case (file_exists(server_root() . $file = '/uploads/product_types/mockups/' . $this->slug . '/' . $color->slug . '.png')):
					$image = $file;
					break;
				default:
					$image = null;
			}

			$this->data['images'][$imageSlug] = $image;
		}

		return $image;
	}

	public function getDetailsImage(int $colorId, int $position_id = null) {
		if (!$position_id) $position_id = $this->basicPositionId;

		$color = new Color($colorId);
		$imageSlug = $color->slug . '-' . $position_id;
		$image = $this->data['detail-images'][$imageSlug] ?? null;

		if (!$image) {
			switch (true) {
				case (file_exists(SERVER_ROOT . $file = '/uploads/product_types/mockups/' . $this->slug . '/' . $color->slug . '-' . $position_id . '-det.webp')):
					$image = $file;
					break;
				case (file_exists(SERVER_ROOT . $file = '/uploads/product_types/mockups/' . $this->slug . '/' . $color->slug . '-' . $position_id . '-det.png')):
					$image = $file;
					break;
				case (file_exists(SERVER_ROOT . $file = '/uploads/product_types/mockups/' . $this->slug . '/' . $color->slug . '-det.png')):
					$image = $file;
					break;
				default:
					$image = null;
			}

			$this->data['detail-images'][$imageSlug] = $image;
		}

		return $image;
	}
}
