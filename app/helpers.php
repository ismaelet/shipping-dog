<?php

use App\Helpers\EcommerceHelper;
use App\Helpers\FileHelper;
use App\Models\Ecommerce\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Debug an array
 */
function dd_array(array $array): void {
	dd('<pre>' . print_r($array, true) . '</pre>');
}

/**
 * Check if the user is an admin
 */
function is_admin(): bool {
	$user = User::find(Auth::id());

	return Auth::check() && $user->isAdmin();
}

/**
 * Check if the user is a supervisor
 */
function is_supervisor(): bool {
	$user = User::find(Auth::id());

	return Auth::check() && $user->isSupervisor();
}

/**
 * Get the path of a file in the public storage directory
 */
function public_asset(string $path): string {
	return FileHelper::publicAsset($path);
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
 * Get the ID of the active cart
 */
function cart_id(): ?int {
	return EcommerceHelper::cart_id();
}

/**
 * Get the active cart
 */
function cart(): ?Cart {
	return EcommerceHelper::cart();
}

/**
 * Get the number of items in the cart
 */
function cart_quantity(): int {
	return EcommerceHelper::cartQuantity();
}
