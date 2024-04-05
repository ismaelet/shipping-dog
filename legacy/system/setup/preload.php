<?php

function preload() {

	global $view;

	foreach (ViewOld::$preload as $file) echo '<link rel="preload" as="image" href="' . $file . '">
	';
}
