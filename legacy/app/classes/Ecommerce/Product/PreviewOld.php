<?php

namespace Legacy\Classes\Ecommerce\Product;

use LegacyDB;
use Legacy\Classes\Ecommerce\Product as LegacyProduct;
use \Legacy\System\Traits\DynamicProperties;

class PreviewOld {

	use \Legacy\System\Traits\DynamicProperties;

	public const SMALL_IMAGE_SIZE = 400;
	public const BIG_IMAGE_SIZE = 1024;
	public const DEFAULT_IMAGE_SIZE = self::SMALL_IMAGE_SIZE;
	public const ALLOWED_IMAGE_SIZES = [self::SMALL_IMAGE_SIZE, self::BIG_IMAGE_SIZE];

	private $product;
	private $imageSize;

	function __construct(object $product, $imageSize) {
		$this->product = $product;
		$this->imageSize = in_array($imageSize, self::ALLOWED_IMAGE_SIZES) ? $imageSize : self::DEFAULT_IMAGE_SIZE;
	}

	/*
	* Get methods
	*/
	public function getImage() {
		$image = $this->filePath;

		if (!$this->getHasImage()) {
			if ($this->product->id && !$this->product->deleted && !$this->product->isCustom) {
				$this->generateImage();
			}

			if (!$this->getHasImage()) {
				$image = '/uploads/product_types/mockups/no-image';
			}
		}

		return $image;
	}

	private function getHasImage() {
		return file_exists($this->serverFilePath);
	}

	private function getFileName() {
		return $this->product->id . '-' . $this->product->color->slug . '-' . $this->product->position_id . '-' . $this->imageSize;
	}

	private function getDirectory() {
		$typeSlug = $this->product->isCustom ? 'custom' : $this->product->type->slug;

		return '/uploads/products/' . $typeSlug;
	}

	private function getServerDirectory() {
		return public_server_directory($this->directory);
	}

	private function getFilePath() {
		return $this->directory . '/' . $this->fileName;
	}

	private function getServerFilePath() {
		return public_server_path($this->filePath);
	}

	/*
	* OLD
	*/
	static function put($id, $data) {
		$result = false;
		$product = new LegacyProduct($id);
		$base = \Legacy\System\Classes\LegacyDB::row('* FROM items_mockups WHERE type_id = ? AND position_id = ?', [$product->type->id, $data['position_id']]);

		if ($data['size'] != $base['siz_x_def'] || $data['pos-x'] != $base['pos_x_def'] || $data['pos-y'] != $base['pos_y_def']) {
			if (\Legacy\System\Classes\LegacyDB::exists('items_previews WHERE product_id = ? AND position_id = ?', [$product->id, $data['position_id']]))
				$result = \Legacy\System\Classes\LegacyDB::update('items_previews SET size=?, pos_x=?, pos_y=? WHERE product_id = ? AND position_id = ?', [$data['size'], $data['pos-x'], $data['pos-y'], $product->id, $data['position_id']]);
			else
				$result = \Legacy\System\Classes\LegacyDB::add('items_previews (product_id, position_id, size, pos_x, pos_y) VALUES (?, ?, ?, ?, ?)', [$product->id, $data['position_id'], $data['size'], $data['pos-x'], $data['pos-y']]);

			self::deleteImages($product->id);
		}

		return $result;
	}

	// generate a missing image
	private function generateImage() {
		// fetch mockup base data
		$mockupFrameworkData = \Legacy\System\Classes\LegacyDB::row('* FROM items_mockups WHERE type_id = ? AND position_id = ?', [$this->product->type_id, $this->product->position_id]);

		// fetch mockup design data
		$mockupProductData = \Legacy\System\Classes\LegacyDB::row('* FROM items_previews WHERE product_id = ? AND position_id = ?', [$this->product->id, $this->product->position_id]);

		// background
		$backgroundImage = server_root() . '/uploads/product_types/mockups/bgr/grey.png';

		// clothing
		$clothingImage = server_root() . $this->product->type->getImage($this->product->color_id, $this->product->position_id);
		$clothingImageType = strpos($clothingImage, '.webp') ? 'webp' : 'png';

		$details = file_exists($file = str_replace('.' . $clothingImageType, '-det.' . $clothingImageType, $clothingImage)) ? $file : false;

		// design
		$design = $this->product->color->dark && file_exists($file = server_root() . '/uploads/designs/' . $this->product->design_id . '_dark.png') ?
			$file : server_root() . '/uploads/designs/' . $this->product->design_id . '.png';

		// generate image
		if (!file_exists($clothingImage)) {
			error_log('Doesn\'t exist clothing file: ' . $clothingImage);
		} else if (!file_exists($design)) {
			error_log('Doesn\'t exist design file: ' . $design . ' of product: ' . $this->product->id);
		} else if (!$mockupProductData && !$mockupFrameworkData) {
			error_log('Doesn\'t exist mockup data for product: ' . $this->product->id);
		} else {
			// create the directory if it doesn't exist
			if (!file_exists($this->serverDirectory)) {
				mkdir($this->serverDirectory, 0777, true);
			}

			$x = $y = $this->imageSize;
			$out = imagecreatetruecolor($x, $y);

			// add background
			$srcImage = imagecreatefrompng($backgroundImage);
			$src_size = getimagesize($backgroundImage);
			$src_w = $src_size[0];
			$src_h = $src_size[1];

			imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);

			// add clothing
			$srcImage =  $clothingImageType == 'webp' ? imagecreatefromwebp($clothingImage) : imagecreatefrompng($clothingImage);
			$src_size = getimagesize($clothingImage);
			$src_w = $src_size[0];
			$src_h = $src_size[1];

			if (!isset($src_size[1])) {
				error_log('No valid clothing image for product: ' . $this->product->id . ' and type: ' . ($this->prouct->type->name ?? null) . ' and color: ' . ($this->prouct->color->name ?? null) . ' and position: ' . ($this->prouct->position->name ?? null));
			}

			if ($srcImage) {
				imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);

				// add design
				$srcImage = imagecreatefrompng($design);
				$src_size = getimagesize($design);
				$src_w = $src_size[0];
				$src_h = $src_size[1];

				$sX = $sY = $mockupProductData ? $mockupProductData['size'] / 100 : $mockupFrameworkData['siz_x_def'] / 100;
				$pX = $mockupProductData ? $mockupProductData['pos_x'] / 100 : $mockupFrameworkData['pos_x_def'] / 100;
				$pY = $mockupProductData ? $mockupProductData['pos_y'] / 100 : $mockupFrameworkData['pos_y_def'] / 100;
				$pX_min = $mockupFrameworkData['pos_x_min'] / 100;
				$pY_min = $mockupFrameworkData['pos_y_min'] / 100;
				$sX_max = $mockupFrameworkData['siz_x_max'] / 100;
				$sY_max = $mockupFrameworkData['siz_y_max'] / 100;

				$ratio = $src_w / $src_h;
				$land = ($ratio > $sX_max / $sY_max) ? true : false;

				if ($land) {
					$des_w = round($x * $sX_max * $sX);
					$des_h = round($des_w / $ratio);
				} else {
					$des_h = round($y * $sY_max * $sY);
					$des_w = round($des_h * $ratio);
				}

				$des_x = round(($x * $pX_min) + ($x * $sX_max - $des_w) * $pX);
				$des_y = round(($y * $pY_min) + (($y * $sY_max - $des_h)) * $pY);

				imagecopyresampled($out, $srcImage, $des_x, $des_y, 0, 0, $des_w, $des_h, $src_w, $src_h);

				// add details
				if (file_exists($details)) {
					$srcImage = $clothingImageType == 'webp' ? imagecreatefromwebp($details) : imagecreatefrompng($details);
					$src_size = getimagesize($details);
					$src_w = $src_size[0];
					$src_h = $src_size[1];

					imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);
				}

				// generate image
				// imagewebp($out, $this->serverFilePath, $this->imageSize >= 1000 ? 90 : 70);
				// imagedestroy($out);
			} else {
				error_log('Missing/broken source image for preview for product ' . $this->product->id);
			}
		}
	}

	// delete images
	static function deleteImages($id) {
		$product = new LegacyProduct($id);

		$files = glob(public_storage('products/' . $product->type->slug . '/' . $product->id . '-*'));
		array_map('unlink', $files);
	}
}
