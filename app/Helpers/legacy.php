<?php

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

function sys_config($field) {
	global $sysConfig;

	include_once(base_path() . '/legacy/app/config/config.php');

	return $sysConfig[$field] ?? null;
}

function ecommerce_config($paramenter) {
	global $ecommerceConfig;

	if (empty($ecommerceConfig)) include_once(base_path() . '/legacy/app/config/ecommerce-config.php');

	return $ecommerceConfig[$paramenter];
}

function format_price($price, $alwaysNumber = true) {
	if ($price || $alwaysNumber) {
		switch (ecommerce_config('currency')) {
			case 'BRL':
				$priceString = 'R$ ' . formatc($price);
				break;
			case 'EUR':
			default:
				$priceString = formatc($price) . ' €';
		}
	} else {
		$priceString = ('Free');
	}

	return $priceString;
}

function rows_to_objects(Query_Result $rows = null, string $className) {
	$objects = [];

	while ($row = $rows->fetch_assoc()) {
		$objects[] = new $className($row[$className::INDEX]);
	}

	return $objects;
}

function warning_banner() {

	$warning['text'] = \Legacy\System\Classes\LegacyDB::find('value FROM config WHERE name = ?', ['warning']);
	$warning['color'] = \Legacy\System\Classes\LegacyDB::find('value FROM config WHERE name = ?', ['warning_color']);
	$warning['start'] = \Legacy\System\Classes\LegacyDB::find('value FROM config WHERE name = ?', ['warning_start']);
	$warning['end'] = \Legacy\System\Classes\LegacyDB::find('value FROM config WHERE name = ?', ['warning_end']);

	if (empty($warning['text']) || strtotime($warning['start']) > strtotime('now') || strtotime($warning['end'] . '+1 day') < strtotime('now')) $warning = false;

	return $warning;
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
		$url = assets_directory(\Legacy\System\Classes\LegacyView::$url) . $file;
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
