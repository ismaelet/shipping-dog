<?php

//load all the page scripts
function scripts($view) {

	//load the framework scripts
	$files = glob(SERVER_ROOT . '/system/scripts/*.js');
	foreach ($files as $file) load_script($file);

	//load the app scripts
	$files = glob(SERVER_ROOT . '/app/scripts/*.js');
	foreach ($files as $file) load_script($file);

	//load the template and page scripts
	if (file_exists($file = SERVER_ROOT . '/app/templates/scripts/' . ViewOld::$template . '.js')) load_script($file);
	else if (file_exists($file = SERVER_ROOT . '/system/templates/scripts/' . ViewOld::$template . '.js')) load_script($file);

	if (file_exists($file = SERVER_ROOT . '/pages/scripts/' . ViewOld::$name . '.js')) load_script($file);

	//load the scripts in the page js folder, if present
	$files = glob(SERVER_ROOT . '/pages/scripts/' . ViewOld::$name . '/*.js');
	foreach ($files as $file) load_script($file);

	//load additional scripts, if present
	foreach (ViewOld::$scripts as $file) load_script($file);
}

//load a script
function load_script($file) {

	$file .= '?v=' . filemtime($file);

	echo '<script src="' . parse_html($file) . '"></script>
';
}
