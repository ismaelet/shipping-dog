<?php

namespace App\Helpers;

class FileHelper {

	private const SVG_PATH = '/resources/svg';
	private const ICONS_PATH = self::SVG_PATH . '/icons';

	/**
	 * Get the path of a file in the public storage directory
	 */
	public static function publicStoragePath(string $path): string {
		return storage_path('app/public/' . $path);
	}

	/**
	 * Import an icon and embed it in the HTML
	 */
	public static function embedSvg(string $fileName, array $customClasses = [], bool $isIcon = false): ?string {
		// Add the default class and any custom classes
		$classes = implode(' ', ['icon-embed', ...$customClasses]);

		// Get the icon file
		$fileDirectory = $isIcon ? self::ICONS_PATH : self::SVG_PATH;
		$filePath = base_path() . $fileDirectory . '/' . $fileName . '.svg';

		// If the icon doesn't exist, return null
		if (!file_exists($filePath)) return null;

		// Get the icon content
		$file = file_get_contents($filePath);

		// Add the classes to the icon and return it
		return str_replace('<svg', '<svg class="' . $classes . '"', $file);
	}
}
