<?php

function preload() {

	global $view;

	foreach (\Legacy\System\Classes\LegacyView::$preload as $file) echo '<link rel="preload" as="image" href="' . $file . '">
	';
}
