<?php

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use App\Legacy\Ecommerce\ViewManager;
use Illuminate\Support\Facades\Vite;
use Legacy\System\Classes\Query_Result;

function client_url(string $parcialUrl = '') {
	return $parcialUrl;
}

function client_path(string $file = '') {
	$url = asset('' . file_time(generate_base_url($file)));
	return $url;
}

function file_time($file) {
	if (!strpos($file, '?=') && file_exists($url = base_path() . '/legacy' . $file)) $file .= '?=' . filemtime($url);

	return $file;
}

function info_box($text) { ?>

	<div class="tip-box inlb clickable">

		<div class="icon-box">
			<?= embed_icon('question') ?>
		</div>

		<p class="tip-icon-txt"><?= $text ?></p>

	</div>

<?php }

function try_include(string $filePath) {
	if (file_exists($filePath)) {
		include($filePath);
	}
}

function sort_subvalue(&$array, $key) {
	usort($array, function ($a, $b) use ($key) {
		return $a[$key] <=> $b[$key];
	});
}

function new_url(string $parcialUrl = '') {
	return $parcialUrl;
}

function explode_last_legacy($separator, $string) {
	$array = explode($separator, $string);
	return end($array);
}

function redirect_old($path = false) {
	if (empty($path)) {
		$url = $_SERVER['REQUEST_URI'];
	} else if ($path[0] == '?') {
		$url = explode('?', $_SERVER['REQUEST_URI'])[0] . $path;
	} else if ($path[0] == '#') {
		$url = explode('#', $_SERVER['REQUEST_URI'])[0] . $path;
	} else if ($path[0] == '&') {
		$url = $_SERVER['REQUEST_URI'] . $path;
	} else if ($path[0] == '/') {
		$url = client_url($path);
	} else {
		$url = $path;
	}

	header('Location: ' . $url);
	exit();
}

function ecommerce_config($paramenter) {
	global $ecommerceConfig;

	if (empty($ecommerceConfig)) include_once(base_path() . '/legacy/app/config/ecommerce-config.php');

	return $ecommerceConfig[$paramenter];
}

function rows_to_objects(Query_Result $rows = null, string $className) {
	$objects = [];

	while ($row = $rows->fetch_assoc()) {
		$objects[] = new $className($row[$className::INDEX]);
	}

	return $objects;
}

function extract_markup(string $markup, string $content): string|null {
	if (strpos($content, '<' . $markup . '>') !== false) {
		return explode('</' . $markup . '>', explode('<' . $markup . '>', $content, 2)[1], 2)[0];
	} else {
		return null;
	}
}


function generate_base_url(string $file = '') {
	if (!strpos($file, '.')) {
		$file .= '.webp';
	}

	if ($file[0] == '/') {
		$url = $file;
	} else {
		$url = 'images/' . $file;
	}

	return $url;
}

function server_path(string $file = '') {
	$url = base_path() . "/legacy/" . generate_base_url($file);

	return $url;
}

function public_server_path(string $file = '') {
	$url = server_path($file);

	return $url;
}

function public_client_url(string $parcialUrl = '') {
	$url = client_url($parcialUrl);

	return $url;
}

function background_image($img) {
	return 'style="background-image: url(' . Vite::image($img) . ');"';
}

function assets_directory($page_url): string|null {
	if ($page_url) {
		$url = substr($page_url, strpos($page_url, 'pages') - 1);
		$url = str_replace(['/views/', '.php'], ['/assets/', '/'], $url);
	} else {
		$url = null;
	}

	return $url;
}

function des_img_gen(int $design_id): void {

	$design = Design::find($design_id);

	//design path
	$des_path = public_storage('designs/' . $design->id . '.png');

	//outputs
	$dst_image_path = public_storage('designs/previews/' . $design->id . '.webp');
	$dst_image_path_m = public_storage('designs/previews/' . $design->id . '_m.webp');

	if (file_exists($des_path)) {

		//make directory if doesn't exist
		if (!file_exists($dir = public_storage('designs/previews'))) {
			mkdir($dir, 0777, true);
		}

		//SAVE HD
		$w = $h = 1024;
		$w_m = $h_m = 400;

		$dst_image = imagecreatetruecolor($w, $h);

		//bgr
		$src_image = imagecreatetruecolor($w, $h);

		if (isset($design->color)) $design->color = DB::find('color FROM designs WHERE id = ?', [$design->id]);

		$rgba = parse_rgba(DB::find('color FROM designs WHERE id = ?', [$design->id]));
		$color = imagecolorallocate($src_image, $rgba[0], $rgba[1], $rgba[2]);

		imagefilledrectangle($src_image, 0, 0, $w - 1, $h - 1, $color);

		imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w, $h, $w, $h);

		//design
		$src_image = imagecreatefrompng($des_path);

		$old_size = getimagesize($des_path);
		$src_w = $old_size[0];
		$src_h = $old_size[1];
		$ratio = $src_w / $src_h;

		if ($ratio > 1) {
			$dst_w = $w;
			$dst_h = $h / $ratio;
			$dst_x = 0;
			$dst_y = ($h - $dst_h) / 2;
		} else {
			$dst_w = $w * $ratio;
			$dst_h = $h;
			$dst_x = ($w - $dst_w) / 2;
			$dst_y = 0;
		}

		imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
		imagewebp($dst_image, $dst_image_path, 90);

		imagedestroy($src_image);
		imagedestroy($dst_image);

		//SAVE LD
		$src_image = imagecreatefromwebp($dst_image_path);
		$dst_image = imagecreatetruecolor($w_m, $h_m);

		imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $w_m, $h_m, $w, $h);
		imagewebp($dst_image, $dst_image_path_m, 80);

		imagedestroy($src_image);
		imagedestroy($dst_image);
	}
}

// parse colors
function parse_rgba($hex, $alpha = 1) {
	$rgba = [];
	$hex = str_replace('#', '', $hex);

	for ($i = 0; $i < 3; $i++) $rgba[] = hexdec(substr($hex, $i * 2, 2));

	$rgba[] = $alpha;

	return $rgba;
}

function random_string($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';

	$charactersLength = strlen($characters);

	$randomString = '';

	for ($i = 0; $i < $length; $i++) $randomString .= $characters[rand(0, $charactersLength - 1)];

	return $randomString;
}
