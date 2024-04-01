<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;

/**
 * Import an svg file
 */
function import_svg(string $filename): void {
	echo File::get(resource_path('svg/' . $filename . '.svg'));
}

/**
 * Set the css background-image property
 */
function set_background_image(string $fileName): string {
	return 'background-image: url(' . Vite::image($fileName) . ')';
}
