<?php

//load shop function
function plugin_tilt_jquery() {

	global $view;

	ViewOld::$scripts->add(__DIR__ . '/script.js');
}
