<?php

/**
 * Urls
 */

function public_client_path(string $file = '') {
	if (defined('PUBLIC_CLIENT_ROOT')) {
		$url = PUBLIC_CLIENT_ROOT . file_time(generate_base_url($file));
	} else {
		$url = client_path($file);
	}

	return $url;
}

function public_server_directory(string $path = '') {
	if (defined('PUBLIC_SERVER_ROOT')) {
		$url = PUBLIC_SERVER_ROOT . $path;
	} else {
		$url = SERVER_ROOT . $path;
	}

	return $url;
}

// put the image as background image
function background_image($img) {
	return 'style="background-image: url(' . client_path($img) . ');"';
}

function temp_bg($img) {
	$img = str_replace(asset('/storage'), '', $img);

	return 'style="background-image: url(' . public_asset($img) . ');"';
}

// put a svg as background image
function img_svg($img) {
	return 'style="mask: url(' . client_path($img) . '); -webkit-mask: url(' . client_path($img) . ') center;"';
}

// get the raw url of an icon
function icon_url_raw($file) {
	$file = $file . '.svg';

	if ($file[0] == '/') $urlRaw = $file;
	else if (file_exists(SERVER_ROOT . $path = assets_directory(\Legacy\System\Classes\LegacyView::$url) . $file)) $urlRaw = $path;
	else if (file_exists(SERVER_ROOT . $path = APP_ICONS_DIR . $file)) $urlRaw = $path;
	else $urlRaw = ICONS_DIR . $file;

	return $urlRaw;
}

// get the client url of an icon
function icon_url($file) {
	$urlRaw = icon_url_raw($file);
	$url = CLIENT_ROOT . file_time($urlRaw);

	return $url;
}

// get an embedded icon
function icon_embed(string $file, array $customClasses = []) {
	$classes = implode(' ', ['svg-icon', ...$customClasses]);

	$url = SERVER_ROOT . icon_url_raw($file);
	$icon = file_get_contents($url);
	$icon = str_replace('<svg', '<svg class="' . $classes . '"', $icon);

	return $icon;
}

// put an icon as background image
function icon_css($icon) {
	return 'style="background-image: url(' . icon_url($icon) . ');"';
}

// get the url of a page's image folder 
function assets_directory($page_url): string|null {
	if ($page_url) {
		$url = substr($page_url, strpos($page_url, 'pages') - 1);
		$url = str_replace(['/views/', '.php'], ['/assets/', '/'], $url);
	} else {
		$url = null;
	}

	return $url;
}

// parse a url from server url to client url
function parse_html($url, $full = true) {
	$url = str_replace("\\", "/", $url);

	if (strpos($url, SERVER_ROOT) !== false) $url = $full ? CLIENT_ROOT . '/' . substr($url, strlen(SERVER_ROOT) + 1) : '/' . substr($url, strlen(SERVER_ROOT) + 1);

	return $url;
}

// parse a url from client url to server url
function parse_server_path($url) {
	if (strpos($url, CLIENT_ROOT) !== false) $url = SERVER_ROOT . explode(CLIENT_ROOT, $url)[1];
	return $url;
}

// get all files in a folder
function scan(string $path, string $ext = '*', string $filter = '*') {
	$files = [];

	if (strpos($path, SERVER_ROOT) === false) $path = SERVER_ROOT . $path;

	foreach (glob($path . '/' . $filter . '.' . $ext) as $file) $files[] = $file;

	return $files;
}

// get all files in the subfolders of a folder
function scans($path, $ext = '*') {
	$files = [];

	foreach (glob(SERVER_ROOT . $path . '/*', GLOB_ONLYDIR) as $dir) array_push($files, ...scan($dir, $ext));

	return $files;
}

// get the server root
function force_base_url(string $filePath) {
	return strpos($filePath, SERVER_ROOT) !== false ? explode(SERVER_ROOT, $filePath)[1] : $filePath;
}

// get the server root
function server_root() {
	return defined('PUBLIC_SERVER_ROOT') ? PUBLIC_SERVER_ROOT : SERVER_ROOT;
}

// get the public server root
function public_server_root() {
	return server_root();
}

/*
* Redirect
*/


/**
 * Uploads
 */
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
 * @param array $fileData 
 * url: the absolute destination path
 * size: the size of the image, used by "mode"
 * ?name [file]: the name of the file input
 * ?key: the key of the file input, in case of multiple uploads from the same input
 * ?type [webp]: the type of the destination image (webp, jpg, png)
 * ?mode [min]: the re-size method:
 * 		min: the smallest side will be of the set size
 * 		max: the largest side will be of the set size
 * 		crop: crop following the set size
 * ?quality [80]: the compression quality, not used for PNG
 * @return $error
 */
function upload_img($fileData) {

	$error = false;

	$url = $fileData['url'];
	$size = $fileData['size'];
	$type = isset($fileData['type']) && in_array($fileData['type'], ['webp', 'jpg', 'png']) ? $fileData['type'] : 'webp';
	$mode = isset($fileData['mode']) && in_array($fileData['mode'], ['width', 'height', 'min', 'max', 'crop']) ? $fileData['mode'] : 'min';
	$quality = $fileData['quality'] ?? 80;

	//get file data
	if (isset($fileData['key'])) {
		$srcImg = $_FILES[$fileData['name'] ?? 'file']['tmp_name'][$fileData['key']];
		$fileName = $_FILES[$fileData['name'] ?? 'file']['name'][$fileData['key']];
		$fileSize = $_FILES[$fileData['name'] ?? 'file']['size'][$fileData['key']];
		$fileConf = $_FILES[$fileData['name'] ?? 'file']['error'][$fileData['key']];
	} else {
		$srcImg = $_FILES[$fileData['name'] ?? 'file']['tmp_name'];
		$fileName = $_FILES[$fileData['name'] ?? 'file']['name'];
		$fileSize = $_FILES[$fileData['name'] ?? 'file']['size'];
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
