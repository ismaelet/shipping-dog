<?php

function preload() {

	global $view;

	foreach (View::$preload as $file) echo '<link rel="preload" as="image" href="' . $file . '">
	';
}
