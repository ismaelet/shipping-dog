<?php

use App\Helpers\EcommerceHelper;
use App\Helpers\FileHelper;
use App\Models\Ecommerce\Cart;
use User as OldUser;
use Ecommerce\Cart as EcommerceCart;
use Illuminate\Support\Facades\Auth;

/**
 * Debug a variable
 */
function debug($var): void {
	echo '<pre>';
	print_r($var);
	echo '</pre>';
	exit;
}

/**
 * Check if the user is an admin
 */
function is_admin(): bool {
	return Auth::check() && Auth::user()->isAdmin();
}

/**
 * Check if the user is a supervisor
 */
function is_supervisor(): bool {
	return Auth::check() && Auth::user()->isSupervisor();
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
function public_storage_path(string $path): string {
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


//--------------------



function cartId(): ?int {
	return EcommerceHelper::cartId();
}

function is_seller(): bool {
	return Auth::check() && Auth::user()->isSeller();
}

function user_old(): ?OldUser {
	return new OldUser(Auth::id() ?? 0);
}

function cart_old(): ?EcommerceCart {
	return new EcommerceCart(cart()->ID ?? 0);
}
