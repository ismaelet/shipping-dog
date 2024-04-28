<?php

//load shop function
function plugin_tilt_jquery() {

	global $view;

	\Legacy\System\Classes\LegacyView::$scripts->add(__DIR__ . '/script.js');
}
