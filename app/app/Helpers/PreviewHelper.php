<?php

namespace App\Helpers;

use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductMockup;
use App\Models\Ecommerce\Product\ProductPosition;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Product\ProductTypeMockupSize;
use Exception;
use Illuminate\Support\Facades\Log;

class PreviewHelper {

	public const SMALL_IMAGE_SIZE = 400;
	public const BIG_IMAGE_SIZE = 1024;
	public const DEFAULT_IMAGE_SIZE = self::SMALL_IMAGE_SIZE;
	public const ALLOWED_IMAGE_SIZES = [self::SMALL_IMAGE_SIZE, self::BIG_IMAGE_SIZE];
	public const PLACEHOLDER_IMAGE = 'product_types/mockups/no-image.webp';

	private int $imageSize;
	private Product $product;
	private ProductColor $color;
	private ProductPosition $position;

	function __construct(Product $product, ProductColor $color, ProductPosition $position, ?int $imageSize = self::SMALL_IMAGE_SIZE) {
		// Set the image size
		$this->imageSize = in_array($imageSize, self::ALLOWED_IMAGE_SIZES) ? $imageSize : self::DEFAULT_IMAGE_SIZE;

		// Set the product
		$this->product = $product;

		// Set the color
		if (is_object($color)) {
			$this->color = $color;
		} else if (is_int($color) && $color = ProductColor::find($color)) {
			$this->color = $color;
		} else {
			$this->color = $product->color;
		}

		// Set the position
		if (is_object($position)) {
			$this->position = $position;
		} else if (is_int($position)) {
			$this->position = ProductPosition::find($position);
		} else {
			$this->position = $product->position;
		}
	}

	/*
	* Returns the image path
	*/
	public function image(): string {
		if ($this->product && $this->color && $this->position) {
			$image = $this->fileStoragePath();

			// Return the image path if it exists
			if ($this->hasImage()) {
				return public_asset($image);
			}

			// Otherwise generate the image
			if ($this->product->id && !$this->product->isCustom()) {
				$this->generateImage();
			}

			// Try again to return the image path
			if ($this->hasImage()) {
				return public_asset($image);
			}
		}

		// Return the default image path
		return public_asset(self::PLACEHOLDER_IMAGE);
	}

	public function exists(): bool {
		return $this->hasImage();
	}

	private function fileName(): string {
		return $this->product->id . '-' . $this->color->tag . '-' . $this->position->id . '-' . $this->imageSize . '.webp';
	}

	private function storageDirectory(): string {
		return !$this->product->isCustom() ? 'products/' . $this->product->type->tag : 'products/custom';
	}

	private function fileStoragePath(): string {
		return $this->storageDirectory() . '/' . $this->fileName();
	}

	private function fileServerPath(): string {
		return public_storage($this->fileStoragePath());
	}

	private function hasImage(): bool {
		return file_exists($this->fileServerPath());
	}

	/**
	 * Generate the image
	 */
	private function generateImage(): void {
		$design = $this->product->design_with_trashed;

		// Fetch mockup type data
		$mockupFrameworkData = ProductTypeMockupSize::where('type_id', $this->product->type_id)->where('position_id', $this->position->id)->first();

		// Fetch mockup product data
		$mockupProductData = ProductMockup::where('product_id', $this->product->id)->where('position_id', $this->position->id)->first();

		// Background image
		$backgroundImage = public_storage('/product_types/mockups/bgr/grey.png');

		// Clothing images
		$clothingImage = public_storage($this->product->type->image($this->color->id, $this->position->id));
		$clothingImageType = strpos($clothingImage, '.webp') ? 'webp' : 'png';

		$details = file_exists($file = str_replace('.' . $clothingImageType, '-det.' . $clothingImageType, $clothingImage)) ? $file : false;

		// Design image
		$designImage = $this->color->dark && file_exists($file = public_storage('/designs/' . $design->id . '_dark.png')) ?
			$file : public_storage('/designs/' . $design->id . '.png');

		// Check if every required image exists
		if (!file_exists($clothingImage)) {
			error_log('Doesn\'t exist clothing file: ' . $clothingImage);

			if (config('app.debug')) {
				dd('Doesn\'t exist clothing file: ' . $clothingImage, $this->product->type->image($this->color->id, $this->position->id), $this->color, $this->position);
			}

			return;
		}

		if (!file_exists($designImage)) {
			error_log('Doesn\'t exist design file: ' . $designImage . ' of product: ' . $this->product->id);

			if (config('app.debug')) {
				// dd('Doesn\'t exist design file: ' . $designImage, $design->id, $this->color, $this->position);
			}

			return;
		}

		if (!$mockupProductData && !$mockupFrameworkData) {
			error_log('Doesn\'t exist mockup data for product: ' . $this->product->id);

			if (config('app.debug')) {
				dd('Doesn\'t exist mockup data for product: ' . $this->product->id, $this->product->type->name, $this->color->name, $this->position->name);
			}

			return;
		}

		// Create the directory if it doesn't exist
		if (!file_exists(public_storage($this->storageDirectory()))) {
			mkdir(public_storage($this->storageDirectory()), 0777, true);
		}

		// Create the base image
		$x = $y = $this->imageSize;
		$out = imagecreatetruecolor($x, $y);

		// Add the background
		$srcImage = imagecreatefrompng($backgroundImage);
		$src_size = getimagesize($backgroundImage);
		$src_w = $src_size[0];
		$src_h = $src_size[1];

		imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);

		// Add the clothing image
		try {
			$srcImage = $clothingImageType == 'webp' ? imagecreatefromwebp($clothingImage) : imagecreatefrompng($clothingImage);
		} catch (Exception $e) {
			Log::error('Error creating image from webp', ['Image' => $clothingImage, 'Color' => $this->color->name, 'Position' => $this->position->name, 'Tipo' => $this->product->type->name]);
			return;
		}

		$src_size = getimagesize($clothingImage);
		$src_w = $src_size[0];
		$src_h = $src_size[1];

		if (!isset($src_size[1])) {
			error_log('No valid clothing image for product: ' . $this->product->id . ' and type: ' . ($this->product->type->name ?? null) . ' and color: ' . ($this->color->name ?? null) . ' and position: ' . ($this->position->name ?? null));
			return;
		}

		imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);

		// Add the design image
		$srcImage = imagecreatefrompng($designImage);
		$src_size = getimagesize($designImage);
		$src_w = $src_size[0];
		$src_h = $src_size[1];

		$sX = $sY = $mockupProductData ? $mockupProductData->size / 100 : $mockupFrameworkData->siz_x_def / 100;
		$pX = $mockupProductData ? $mockupProductData->pos_x / 100 : $mockupFrameworkData->pos_x_def / 100;
		$pY = $mockupProductData ? $mockupProductData->pos_y / 100 : $mockupFrameworkData->pos_y_def / 100;
		$pX_min = $mockupFrameworkData->pos_x_min / 100;
		$pY_min = $mockupFrameworkData->pos_y_min / 100;
		$sX_max = $mockupFrameworkData->siz_x_max / 100;
		$sY_max = $mockupFrameworkData->siz_y_max / 100;

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

		// Add the details
		if (file_exists($details)) {
			$srcImage = $clothingImageType == 'webp' ? imagecreatefromwebp($details) : imagecreatefrompng($details);
			$src_size = getimagesize($details);
			$src_w = $src_size[0];
			$src_h = $src_size[1];

			imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);
		}

		// Generate the image
		imagewebp($out, $this->fileServerPath(), $this->imageSize >= 1000 ? 90 : 70);
		imagedestroy($out);
	}

	/**
	 * Generate empty image
	 * 
	 * @param ProductType $type
	 * @param ProductColor $color
	 * @param ProductPosition $position
	 * @param int $imageSize
	 * @return string|null
	 */
	public static function getEmptyImage(ProductType $type, ProductColor $color, ProductPosition $position, int $imageSize = 1024): string|null {
		$directory = 'product_types/rasters';
		$fileName = $type->tag . '-' . $color->tag . '-' . $position->id . '-' . $imageSize . '.webp';
		$filePath = base_path() . '/public/storage/' . $directory . '/' . $fileName;

		if (FileHelper::exists($directory . '/' . $fileName)) {
			return FileHelper::publicAsset($directory . '/' . $fileName);
		}

		// Background image
		$backgroundImage = public_storage('/product_types/mockups/bgr/grey.png');

		// Clothing images
		$clothingImage = public_storage($type->image($color->id, $position->id));
		$clothingImageType = strpos($clothingImage, '.webp') ? 'webp' : 'png';

		$details = file_exists($file = str_replace('.' . $clothingImageType, '-det.' . $clothingImageType, $clothingImage)) ? $file : false;

		// Check if every required image exists
		if (!file_exists($clothingImage)) {
			error_log('Doesn\'t exist clothing file: ' . $clothingImage);
			return null;
		}

		// Create the directory if it doesn't exist
		if (!file_exists(public_storage($directory))) {
			mkdir(public_storage($directory), 0777, true);
		}

		// Create the base image
		$x = $y = $imageSize;
		$out = imagecreatetruecolor($x, $y);

		// Add the background
		$srcImage = imagecreatefrompng($backgroundImage);
		$src_size = getimagesize($backgroundImage);
		$src_w = $src_size[0];
		$src_h = $src_size[1];

		imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);

		// Add the clothing image
		try {
			$srcImage = $clothingImageType == 'webp' ? imagecreatefromwebp($clothingImage) : imagecreatefrompng($clothingImage);
		} catch (Exception $e) {
			Log::error('Error creating image from webp', ['Image' => $clothingImage, 'Color' => $color->name, 'Position' => $position->name, 'Tipo' => $type->name]);
			return null;
		}

		$src_size = getimagesize($clothingImage);
		$src_w = $src_size[0];
		$src_h = $src_size[1];

		if (!isset($src_size[1])) {
			error_log('No valid clothing image for type ' . ($type->name ?? null) . ' and color: ' . ($color->name ?? null) . ' and position: ' . ($position->name ?? null));
			return null;
		}

		imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);

		// Add the details
		if (file_exists($details)) {
			$srcImage = $clothingImageType == 'webp' ? imagecreatefromwebp($details) : imagecreatefrompng($details);
			$src_size = getimagesize($details);
			$src_w = $src_size[0];
			$src_h = $src_size[1];

			imagecopyresampled($out, $srcImage, 0, 0, 0, 0, $x, $y, $src_w, $src_h);
		}

		// Generate the image
		imagewebp($out, $filePath, $imageSize >= 1000 ? 90 : 70);
		imagedestroy($out);

		return FileHelper::publicAsset($directory . '/' . $fileName);
	}

	/**
	 * Delete the images
	 */
	public function deleteImages(): void {
		self::deleteProductPreview($this->product);
	}

	/**
	 * Static method to delete all the images
	 */
	public static function deleteProductPreview(Product $product): void {
		$files = glob(public_storage('products/' . $product->type->tag . '/' . $product->id . '-*'));

		array_map(
			function ($file) {
				$file = str_replace(public_storage(), '', $file);
				FileHelper::delete($file);
			},
			$files
		);
	}
}
