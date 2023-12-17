<?php

namespace Ecommerce\Product;

use DB;
use Ecommerce\Product;

class Type {

	public const TABLE = 'items_types';
	public const INDEX = 'ID';
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
	public $positionIds;
	public $category;
	public $class;
	public $custom;
	public $sibling;
	public $blankId;
	public $preorder;
	public $availablePositionIds;

	function __construct($id) {
		if ($row = DB::row('* FROM items_types WHERE ID=?', [$id])) {
			$this->id = $id;
			$this->slug = $row['tag'];
			$this->name = $row['name_' . 'it'];
			$this->names = $row['names_' . 'it'];
			$this->availableColorSlugs = empty($row['colors']) ? ['emp'] : explode(',', $row['colors']);
			$this->sizes = explode(',', $row['sizes']);
			$this->genderId = $row['gender'];
			$this->unisex = $row['unisex'];
			$this->sibling = $row['sibling'];
			$this->category = $row['category'];
			$this->availablePositionIds = explode(',', $row['positions']);
			$this->custom = $row['custom'];
			$this->blankId = $row['blankId'];
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
					return $this->data['blankProduct'] = new Product($this->blankId);

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
		$image = '/app/assets/mockups/' . $this->slug . '.webp';

		if (!file_exists(SERVER_ROOT . $image)) {
			$image = '/app/assets/mockups/' . $this->slug . '.jpg';
		}

		return $image;
	}

	private function getColors(bool $all = true) {
		$colors = [];

		foreach ($this->availableColorSlugs as $colorSlug) {
			$colorId = $all ?
				DB::find('ID FROM items_colors WHERE tag=?', [$colorSlug]) :
				DB::find('ID FROM items_colors WHERE tag=? AND hidden=0', [$colorSlug]);

			if ($colorId) {
				$colors[] = new Color($colorId);
			}
		}

		return $colors;
	}

	private function getPositions() {
		$positions = [];

		foreach ($this->availablePositionIds as $positionId) {
			$position = new Position($positionId);

			if ($position->id) {
				$positions[] = $position;
			}
		}

		return $positions;
	}

	public function getImage(int $colorId, $positionId = null) {
		$color = new Color($colorId);
		$positionId = $positionId ?: $this->basicPositionId;
		$position = new Position($positionId);
		$imageSlug = $color->slug . '-' . $position->imagePositionId;
		$image = $this->data['images'][$imageSlug] ?? null;

		if (!$image) {
			switch (true) {
				case (file_exists(server_root() . $file = '/app/assets/mockups/' . $this->slug . '/' . $color->slug . '-' . $position->imagePositionId . '.webp')):
					$image = $file;
					break;
				case (file_exists(server_root() . $file = '/app/assets/mockups/' . $this->slug . '/' . $color->slug . '-' . $position->imagePositionId . '.png')):
					$image = $file;
					break;
				case (file_exists(server_root() . $file = '/app/assets/mockups/' . $this->slug . '/' . $color->slug . '.png')):
					$image = $file;
					break;
				default:
					$image = null;
			}

			$this->data['images'][$imageSlug] = $image;
		}

		return $image;
	}

	public function getDetailsImage(int $colorId, int $positionId = null) {
		if (!$positionId) $positionId = $this->basicPositionId;

		$color = new Color($colorId);
		$imageSlug = $color->slug . '-' . $positionId;
		$image = $this->data['detail-images'][$imageSlug] ?? null;

		if (!$image) {
			switch (true) {
				case (file_exists(SERVER_ROOT . $file = '/app/assets/mockups/' . $this->slug . '/' . $color->slug . '-' . $positionId . '-det.webp')):
					$image = $file;
					break;
				case (file_exists(SERVER_ROOT . $file = '/app/assets/mockups/' . $this->slug . '/' . $color->slug . '-' . $positionId . '-det.png')):
					$image = $file;
					break;
				case (file_exists(SERVER_ROOT . $file = '/app/assets/mockups/' . $this->slug . '/' . $color->slug . '-det.png')):
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
