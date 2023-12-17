<?php

//load shop function
function plugin_tilt_jquery() {

	global $view;

	View::$scripts->add(__DIR__ . '/script.js');
}
