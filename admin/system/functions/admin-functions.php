<?php

// get url of a file
function file_public_url($file) {

	global $view;

	if ($file[0] == '/') $urlRaw = $file;
	else $urlRaw = assets_directory($view->url) . $file;

	$url = PUBLIC_CLIENT_ROOT . public_file_time($urlRaw);

	return $url;
}

function public_file_time($file) {

	if (!strpos($file, '?=') && file_exists($url = PUBLIC_SERVER_ROOT . $file)) $file .= '?=' . filemtime($url);

	return $file;
}
