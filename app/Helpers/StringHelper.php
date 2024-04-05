<?php

namespace App\Helpers;

class StringHelper {

	/**
	 * Format a number with dot
	 */
	public static function formatWithDot(float $number): string {
		return number_format($number, 2, '.', '');
	}

	/**
	 * Format a number with comma
	 */
	public static function formatWithComma(float $number): string {
		return number_format($number, 2, ',', '');
	}
}
