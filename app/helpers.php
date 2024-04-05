<?php

use App\Helpers\EcommerceHelper;
use App\Helpers\FileHelper;
use App\Models\Ecommerce\Cart;
use App\Models\User;
use User as OldUser;
use Ecommerce\Cart as EcommerceCart;

/**
 * Get the current user
 */
function user(): ?User {
	return auth()->check() ? auth()->user() : null;
}

/**
 * Check if the user is an admin
 */
function is_admin(): bool {
	return auth()->check() && auth()->user()->isAdmin();
}

/**
 * Check if the user is a supervisor
 */
function is_supervisor(): bool {
	return auth()->check() && auth()->user()->isSupervisor();
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

function is_seller(): bool {
	return auth()->check() && auth()->user()->isSeller();
}

function user_old(): ?OldUser {
	return new OldUser(auth()->id() ?? 0);
}

function cart_old(): ?EcommerceCart {
	return new EcommerceCart(cart()->ID ?? 0);
}
