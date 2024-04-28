<?php

//load all the page styles
function styles($view) {

	//load root
	include_once(SERVER_ROOT . '/system/setup/root.php');

	//load framework styles
	$files = glob(SERVER_ROOT . '/system/styles/*.css');
	foreach ($files as $file) load_style($file);

	//load app styles
	$files = glob(SERVER_ROOT . '/app/styles/*.css');
	foreach ($files as $file) load_style($file);

	//load template and page styles
	if (file_exists($file = SERVER_ROOT . '/app/templates/styles/' . \Legacy\System\Classes\LegacyView::$template . '.css')) load_style($file);
	else if (file_exists($file = SERVER_ROOT . '/system/templates/styles/' . \Legacy\System\Classes\LegacyView::$template . '.css')) load_style($file);

	if (file_exists($file = SERVER_ROOT . '/pages/styles/' . \Legacy\System\Classes\LegacyView::$name . '.css')) load_style($file);

	//load styles in the page css folder, if present
	$files = glob(SERVER_ROOT . '/pages/styles/' . \Legacy\System\Classes\LegacyView::$name . '/*.css');
	foreach ($files as $file) load_style($file);

	//load additional styles, if present
	foreach (\Legacy\System\Classes\LegacyView::$styles as $file) load_style($file);
}

//load a style
function load_style($file) {

	$file .= '?v=' . filemtime($file);

	echo '<link href="' . parse_html($file) . '" rel="stylesheet" type="text/css">
';
}
