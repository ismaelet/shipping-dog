<?php

//load shop function
function plugin_side_scroller() {

	global $view;

	View::$styles->add(__DIR__ . '/style.css');
	View::$scripts->add(__DIR__ . '/script.js');
}
