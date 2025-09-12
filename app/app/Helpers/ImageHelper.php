<?php

namespace App\Helpers;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageHelper {

	/**
     * Upload an image
     *
     * @param UploadedFile $file The image to upload
     * @param string $filePath The path to the image
     * @param int|null $size (optional) The size of the image
     * @param string $mode (optional) The mode to resize the image, can be 'width', 'height', 'largest', 'smallest' or 'crop'
     * @param int $quality (optional) The quality of the image, from 0 to 100
     * @return bool Whether the image was uploaded
     */
    public static function upload(UploadedFile $file, string $path, ?int $size = null, string $mode = 'largest', int $quality = 80): bool {
		// Get the directory, file name and type
		[$pathDirectory, $fileName] = explode_last('/', $path);
		$newImageType = pathinfo($path, PATHINFO_EXTENSION);

		// Create the directory if it doesn't exist
		Storage::disk('public')->makeDirectory($pathDirectory);

		// Delete the file if it already exists
		Storage::disk('public')->delete($path);

		// Create the image
		$originalImage = self::createFromFile($file);

		// Calculate the dimensions
		$dimensions = self::calculateDimensions($originalImage, $size, $mode);

		// Create the canvas for the new image
		$canvas = self::createCanvas($dimensions[0], $dimensions[1], $newImageType);

		// Create the new image
		self::create($originalImage, $canvas, $path, $newImageType, $dimensions, $quality);

		// Return whether the image was uploaded
		return FileHelper::exists($path);
	}

	/**
     * Create an image from a file
     *
     * @param UploadedFile $file The file to create the image from
     * @return GdImage|false The created image, or false if the image couldn't be created
     */
    private static function createFromFile(UploadedFile|TemporaryUploadedFile $file): GdImage|false {
		$fileName = is_a($file, TemporaryUploadedFile::class) ? $file->getRealPath() : $file->getPathname();
		$imageType = exif_imagetype($fileName);

		return match ($imageType) {
			IMAGETYPE_WEBP => imagecreatefromwebp($fileName),
			IMAGETYPE_PNG => imagecreatefrompng($fileName),
			IMAGETYPE_JPEG => imagecreatefromjpeg($fileName),
			default => imagecreatefromstring(file_get_contents($fileName)),
		};
	}

	/**
	 * Calculate the dimensions of the image
	 * 
	 * @param GdImage $originalImage The path to the original image
	 * @param int $newSize The size of the image
	 * @param string $mode The mode to resize the image
	 * @return array|false The calculated dimensions, or false if the dimensions couldn't be calculated
	 */
	private static function calculateDimensions(GdImage $originalImage, int $newSize, string $mode): array|false {
		// Get the original image dimensions
		[$originalWidth, $originalHeigth] = [imagesx($originalImage), imagesy($originalImage)];

		// Calculate the ratio
		$ratio = $originalWidth / $originalHeigth;

		// Calculate the new dimensions
		switch ($mode) {
			case 'width':
				return [$newSize, $newSize / $ratio, 0, 0, $originalWidth, $originalHeigth];

			case 'height':
				return [$newSize * $ratio, $newSize, 0, 0, $originalWidth, $originalHeigth];

			case 'largest':
				return $ratio > 1
					? [$newSize, $newSize / $ratio, 0, 0, $originalWidth, $originalHeigth]
					: [$newSize * $ratio, $newSize, 0, 0, $originalWidth, $originalHeigth];

			case 'smallest':
				return $ratio > 1
					? [$newSize * $ratio, $newSize, 0, 0, $originalWidth, $originalHeigth]
					: [$newSize, $newSize / $ratio, 0, 0, $originalWidth, $originalHeigth];

			case 'crop':
				if ($ratio > 1) {
					$x = ($originalWidth - $originalHeigth) / 2;
					return [$newSize, $newSize, $x, 0, $originalHeigth, $originalHeigth];
				} else {
					$y = ($originalHeigth - $originalWidth) / 2;
					return [$newSize, $newSize, 0, $y, $originalWidth, $originalWidth];
				}
		}

		return false;
	}

	/**
     * Create an image
     *
     * @param array $sizes The sizes of the image
     * @return GdImage The created image
     */
    private static function createCanvas(int $width, int $height, string $imageType): GdImage {
		// Create the canvas
		$canvas = imagecreatetruecolor($width, $height);

		// If the image is a PNG or a WebP, make a transparent background
		if (in_array($imageType, ['webp', 'png'])) {
			imagealphablending($canvas, false);
			imagesavealpha($canvas, true);
			$trsp = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
			imagefilledrectangle($canvas, 0, 0, $width, $height, $trsp);
		}

		// Return the canvas
		return $canvas;
	}

	/**
     * Create the image
     *
     * @param GdImage $originalImage The original image
     * @param GdImage $canvas The canvas for the new image
     * @param string $path The path to the new image
     * @param string $type The type of the new image
     * @param array $sizes The sizes of the new image
     * @param int $quality The quality of the new image
     * @return void
     */
    private static function create(GdImage $originalImage, GdImage $canvas, string $path, string $type, array $sizes, int $quality): void {
		[$newWidth, $newHeight, $x, $y, $originalWidth, $originalHeigth] = $sizes;

		imagecopyresampled($canvas, $originalImage, 0, 0, $x, $y, $newWidth, $newHeight, $originalWidth, $originalHeigth);

		match ($type) {
			'webp' => imagewebp($canvas, storage_path('app/public/' . $path), $quality),
			'jpg' => imagejpeg($canvas, storage_path('app/public/' . $path), $quality),
			'png' => imagepng($canvas, storage_path('app/public/' . $path)),
		};

		imagedestroy($originalImage);
		imagedestroy($canvas);
	}
}
