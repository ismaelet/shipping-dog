<?php

start_plugins();

function start_plugins() {

	foreach (glob(SERVER_ROOT . '/app/plugins/*', GLOB_ONLYDIR) as $dir)
		if (file_exists($file = $dir . '/setup.php')) include_once($file);
}
