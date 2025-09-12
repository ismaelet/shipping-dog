<?php

use App\Helpers\App;
use Illuminate\Support\Facades\Auth;

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
 */
function is_superadmin(): bool {
	return Auth::check() && Auth::user()->is_superadmin;
}

/**
 * Check if the user is a seller
 */
function is_seller(): bool {
	return App::isSeller();
}

/**
 * Format a price value according to the current currency.
 */
function price(float $value): string {
	$formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);
	$formatted = $formatter->formatCurrency($value, config('custom.currency', 'EUR'));

	// Replace non-breaking space (&nbsp;) with a regular space
	return str_replace("\u{00A0}", ' ', $formatted);
}
