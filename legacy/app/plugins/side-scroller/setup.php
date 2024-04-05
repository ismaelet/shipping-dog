<?php

//load shop function
function plugin_side_scroller() {

	global $view;

	ViewOld::$styles->add(__DIR__ . '/style.css');
	ViewOld::$scripts->add(__DIR__ . '/script.js');
}
