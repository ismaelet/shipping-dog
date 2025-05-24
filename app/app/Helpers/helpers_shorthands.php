<?php

use App\Helpers\App;
use App\Helpers\ArrayHelper;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Debug an array
 * 
 * @param array $array The array to debug
 * @return void
 */
function dd_array(array $array): void {
	dd('<pre>' . print_r($array, true) . '</pre>');
}

/**
 * Check if the user is an admin
 * 
 * @return bool True if the user is an admin, false otherwise
 */
function is_admin(): bool {
	return Auth::check() && Auth::user()->is_admin;
}

/**
 * Check if the user is a supervisor
 * 
 * @return bool True if the user is a supervisor, false otherwise
 */
function is_superadmin(): bool {
	return Auth::check() && Auth::user()->is_superadmin;
}

/**
 * Check if the user is a seller
 * 
 * @return bool True if the user is a seller, false otherwise
 */
function is_seller(): bool {
	return App::isSeller();
}

/**
 * Get the path of a file in the public storage directory
 */
function public_asset(string $path): string {
	$filePath = FileHelper::publicAsset($path);

	if (!$filePath) {
		Log::error('File not found: ' . $path);
	}

	return $filePath;
}

/**
 * Get the path of a file in the public storage directory
 */
function public_storage(?string $path = null): string {
	return FileHelper::publicStoragePath($path);
}

/**
 * Import an svg and embed it in the HTML
 */
function embed_svg(string $fileName, array $customClasses = []): ?string {
	return FileHelper::embedSvg($fileName, $customClasses);
}

/**
 * Import an icon and embed it in the HTML
 */
function embed_icon(string $fileName, array $customClasses = []): ?string {
	return FileHelper::embedSvg($fileName, $customClasses, true);
}

/**
 * Explode a string by the last occurrence of a separator
 */
function explode_last($separator, $string): array {
	return ArrayHelper::explodeLast($separator, $string);
}
