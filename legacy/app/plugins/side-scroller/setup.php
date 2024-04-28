<?php

//load shop function
function plugin_side_scroller() {

	global $view;

	\Legacy\System\Classes\LegacyView::$styles->add(__DIR__ . '/style.css');
	\Legacy\System\Classes\LegacyView::$scripts->add(__DIR__ . '/script.js');
}
