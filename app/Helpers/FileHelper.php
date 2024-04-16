<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileHelper {

	private const SVG_PATH = '/resources/svg';
	private const ICONS_PATH = self::SVG_PATH . '/icons';

	/**
	 * Get the path of a file in the public assets directory
	 */
	public static function publicStoragePath(string $path): string {
		return storage_path('app/public/' . $path);
	}

	/**
	 * Get the versioned path of a file in the public storage directory
	 */
	public static function publicAsset(string $path): string {
		$publicFilePath = asset('storage/' . $path);
		$storedFilePath = base_path() . '/public/storage/' . $path;
		$fileVersion = file_exists($storedFilePath) ? filemtime($storedFilePath) : 0;

		return $publicFilePath . '?v=' . $fileVersion;
	}

	/**
	 * Move a file to the public storage directory
	 */
	public static function uploadFile(Request $request, string $newFilePath, string $fileName = 'file', bool $overwrite = true): bool {
		$request->validate([
			'file' => ['required', 'file'],
		]);

		$fileToUpload = $request->file($fileName);
		$newFilePath = ArrayHelper::explodeLast('/', $newFilePath);
		$destinationPath = $newFilePath[0];
		$newFileName = $newFilePath[1];

		if ($overwrite && Storage::disk('public')->exists($destinationPath . '/' . $newFileName)) {
			Storage::disk('public')->delete($destinationPath . '/' . $newFileName);
		}

		$newFile = $fileToUpload->storeAs($destinationPath, $newFileName, 'public');

		return (bool) $newFile;
	}

	/**
	 * Upload an image with cropping, resizing, and quality options
	 * 
	 * @param array $fileData 
	 *		@param string 'path': the path of the file in the storage
	 * 		@param int 'size': the size of the image, used by "mode"
	 * 		@param string 'name' = 'file': the name of the file input
	 * 		@param ?int 'key' = null: the key of the file input, in case of multiple uploads from the same input
	 * 		@param string 'type' = 'webp': the type of the destination image (webp, jpg, png)
	 * 		@param string 'mode' = 'min': the re-size method:
	 * 			'min': the smallest side will be of the set size
	 * 			'max': the largest side will be of the set size
	 * 			'crop': crop following the set size
	 * 		@param int quality = 80: the compression quality, not used for PNG
	 * 
	 * @return string|bool
	 */
	public static function uploadImage(array $parameters): string|bool {

		$filePath = $parameters['path'];
		$fileName = ArrayHelper::explodeLast('/', $filePath)[1];
		$storageDirectory = public_storage(ArrayHelper::explodeLast('/', $filePath)[0]);
		$fileStoragePath = $storageDirectory . '/' . $fileName;
		$size = $parameters['size'];
		$type = isset($parameters['type']) && in_array($parameters['type'], ['webp', 'jpg', 'png']) ? $parameters['type'] : 'webp';
		$mode = isset($parameters['mode']) && in_array($parameters['mode'], ['width', 'height', 'min', 'max', 'crop']) ? $parameters['mode'] : 'min';
		$quality = $parameters['quality'] ?? 80;

		// Get file data
		if (isset($parameters['key'])) {
			$srcImg = $_FILES[$parameters['name'] ?? 'file']['tmp_name'][$parameters['key']];
			$fileConf = $_FILES[$parameters['name'] ?? 'file']['error'][$parameters['key']];
		} else {
			$srcImg = $_FILES[$parameters['name'] ?? 'file']['tmp_name'];
			$fileConf = $_FILES[$parameters['name'] ?? 'file']['error'];
		}

		$srcType = exif_imagetype($srcImg);
		$srcResolution = getimagesize($srcImg);
		$srcWidth = $srcResolution[0] ?? 0;
		$srcHeight = $srcResolution[1] ?? 0;

		// Check for errors
		if (!in_array($srcType, [IMAGETYPE_WEBP, IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
			return 'wrong-type';
		}

		if ($fileConf || !$srcWidth || !$srcHeight) {
			return 'file-error';
		}

		// Create the storage directory if it doesn't exist
		if (!file_exists($storageDirectory)) {
			mkdir($storageDirectory, 0777, true);
		}

		// Calculate the new dimensions based on the mode
		$x = 0;
		$y = 0;
		$ratio = $srcWidth / $srcHeight;

		switch ($mode) {
			case 'width':
				$newWidth = $size;
				$newHeight = $size / $ratio;
				break;

			case 'height':
				$newWidth = $size * $ratio;
				$newHeight = $size;
				break;

			case 'max':
				if ($ratio > 1) {
					$newWidth = $size;
					$newHeight = $size / $ratio;
				} else {
					$newWidth = $size * $ratio;
					$newHeight = $size;
				}
				break;

			case 'min':
				if ($ratio > 1) {
					$newWidth = $size * $ratio;
					$newHeight = $size;
				} else {
					$newWidth = $size;
					$newHeight = $size / $ratio;
				}
				break;

			case 'crop':
				list($newWidth, $newHeight) = $size;

				$new_ratio = $newWidth / $newHeight;

				if ($ratio > $new_ratio) {
					$srcWidth = $srcHeight * $new_ratio;
					$x = ($srcResolution[0] - $srcWidth) / 2;
				} else {
					$srcHeight = $srcWidth / $new_ratio;
					$y = ($srcResolution[1] - $srcHeight) / 2;
				}
				break;
		}

		// Start the image creation
		$tempImg = imagecreatetruecolor($newWidth, $newHeight);

		switch ($srcType) {
			case IMAGETYPE_WEBP:
				$originalImg = imagecreatefromwebp($srcImg);
				break;
			case IMAGETYPE_PNG:
				$originalImg = imagecreatefrompng($srcImg);
				break;
			case IMAGETYPE_JPEG:
				$originalImg = imagecreatefromjpeg($srcImg);
				break;
			default:
				imagecreatefromstring(file_get_contents($srcImg));
		}

		// Save the new image
		switch ($type) {
			case 'webp':
				imagealphablending($tempImg, false);
				imagesavealpha($tempImg, true);
				$trsp = imagecolorallocatealpha($tempImg, 255, 255, 255, 127);
				imagefilledrectangle($tempImg, 0, 0, $newWidth, $newHeight, $trsp);
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagewebp($tempImg, $fileStoragePath, $quality);

				break;

			case 'jpg':
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagejpeg($tempImg, $fileStoragePath, $quality);

				break;

			case 'png':
				imagealphablending($tempImg, false);
				imagesavealpha($tempImg, true);
				$trsp = imagecolorallocatealpha($tempImg, 255, 255, 255, 127);
				imagefilledrectangle($tempImg, 0, 0, $newWidth, $newHeight, $trsp);
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagepng($tempImg, $fileStoragePath);

				break;
		}

		imagedestroy($originalImg);
		imagedestroy($tempImg);

		return file_exists($fileStoragePath);
	}


	/**
	 * Import an icon and embed it in the HTML
	 */
	public static function embedSvg(string $fileName, array $customClasses = [], bool $isIcon = false): ?string {
		// Add the default class and any custom classes
		$classes = implode(' ', ['icon-embed', ...$customClasses]);

		// Get the icon file
		$fileDirectory = $isIcon ? self::ICONS_PATH : self::SVG_PATH;
		$filePath = base_path() . $fileDirectory . '/' . $fileName . '.svg';

		// If the icon doesn't exist, return null
		if (!file_exists($filePath)) return null;

		// Get the icon content
		$file = file_get_contents($filePath);

		// Add the classes to the icon and return it
		return str_replace('<svg', '<svg class="' . $classes . '"', $file);
	}
}
