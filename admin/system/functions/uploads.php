<?php

// upload a file
function upload_file($filePath, $name = 'file') {

	$filePath = strpos($filePath, SERVER_ROOT) !== false || (defined('PUBLIC_SERVER_ROOT') && strpos($filePath, PUBLIC_SERVER_ROOT) !== false)  ? $filePath : SERVER_ROOT . '/' . $filePath;
	$src = $_FILES[$name];
	$filePath = explode('/', $filePath);
	$file = array_pop($filePath);
	$directory = implode('/', $filePath);

	if (!file_exists($directory)) mkdir($directory, 0777, true);

	move_uploaded_file($src['tmp_name'], $directory . '/' . $file);
}

/**
 * Upload an image
 * 
 * @param array $fileData [
 * 	'url' => string, the absolute destination path
 * 	'size' => int|array, the size of the image, 2 values if the mode is 'crop', 1 value otherwise
 * 	'name' => string 'file', the name of the file input
 * 	'key' => int null, the key of the file input, in case of multiple uploads from the same input
 * 	'type' => string 'webp', the type of the destination image ['webp', 'jpg', 'png']
 * 	'mode' => string 'min', the re-size method ['min', 'max', 'crop', 'width', 'height']
 * 	'quality' => int 90, the compression quality, not used for PNG [0-100]
 * @return bool
 */
function upload_img($fileData) {

	$error = false;

	$url = $fileData['url'];
	$size = $fileData['size'];
	$type = isset($fileData['type']) && in_array($fileData['type'], ['webp', 'jpg', 'png']) ? $fileData['type'] : 'webp';
	$mode = isset($fileData['mode']) && in_array($fileData['mode'], ['width', 'height', 'min', 'max', 'crop']) ? $fileData['mode'] : 'min';
	$quality = $fileData['quality'] ?? 90;

	//get file data
	if (isset($fileData['key'])) {
		$srcImg = $_FILES[$fileData['name'] ?? 'file']['tmp_name'][$fileData['key']];
		$fileConf = $_FILES[$fileData['name'] ?? 'file']['error'][$fileData['key']];
	} else {
		$srcImg = $_FILES[$fileData['name'] ?? 'file']['tmp_name'];
		$fileConf = $_FILES[$fileData['name'] ?? 'file']['error'];
	}

	$srcType = exif_imagetype($srcImg);
	$srcResolution = getimagesize($srcImg);
	$srcWidth = $srcResolution[0] ?? 0;
	$srcHeight = $srcResolution[1] ?? 0;

	// check for errors
	if (!in_array($srcType, [IMAGETYPE_WEBP, IMAGETYPE_JPEG, IMAGETYPE_PNG])) $error = 'file-type';
	else if ($fileConf || !$srcWidth || !$srcHeight) $error = 'error';
	else {
		$ratio = $srcWidth / $srcHeight;

		$x = $y = 0;

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

		$tempImg = imagecreatetruecolor($newWidth, $newHeight);

		$folder = substr($url, 0, strrpos($url, '/'));

		if (!file_exists($folder)) mkdir($folder, 0777, true);

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

		switch ($type) {
			case 'webp':
				imagealphablending($tempImg, false);
				imagesavealpha($tempImg, true);
				$trsp = imagecolorallocatealpha($tempImg, 255, 255, 255, 127);
				imagefilledrectangle($tempImg, 0, 0, $newWidth, $newHeight, $trsp);
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagewebp($tempImg, $url, $quality);
				break;
			case 'jpg':
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagejpeg($tempImg, $url, $quality);
				break;
			case 'png':
				imagealphablending($tempImg, false);
				imagesavealpha($tempImg, true);
				$trsp = imagecolorallocatealpha($tempImg, 255, 255, 255, 127);
				imagefilledrectangle($tempImg, 0, 0, $newWidth, $newHeight, $trsp);
				imagecopyresampled($tempImg, $originalImg, 0, 0, $x, $y, $newWidth, $newHeight, $srcWidth, $srcHeight);
				imagepng($tempImg, $url);
				break;
		}

		imagedestroy($originalImg);
		imagedestroy($tempImg);
	}

	return $error;
}
