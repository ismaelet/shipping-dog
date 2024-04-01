<?php

use App\Models\Ecommerce\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Request;

define('ICONS_DIR', '/public/legacy/system/assets/icons/');
define('APP_ICONS_DIR', '/public/legacy/app/assets/icons/');

/**
 * User functions
 */
function user() {
	return User::find(0);
}

function userId() {
	return user() ? user()->id : null;
}

function logged() {
	return (bool) userId();
}

function admin() {
	return auth()->user() && auth()->user()->isAdmin();
}

function is_seller() {
	return auth()->user() && auth()->user()->isSeller();
}

/**
 * Cart
 */
function cart() {
	return cartId() ? Cart::find(cartId()) : null;
}

function cartId() {
	if (logged()) {
		return DB::find('ID FROM carts WHERE buyer = ? AND status = 0', [userId()]) ?: null;
	} else {
		return $_SESSION['cartId'] ?? null;
	}
}

function cart_count() {
	return cart() ? cart()->quantity : 0;
}

/**
 * Files
 */
function assets_directory(string $pagePath = null) {
	$path = null;

	if ($pagePath) {
		$path = substr($pagePath, strpos($pagePath, 'pages') - 1);
		$path = str_replace(['/views/', '.php'], ['/assets/', '/'], $path);
	}

	return $path;
}

function icon_url_raw($file) {
	$file = $file . '.svg';

	if ($file[0] == '/') $urlRaw = $file;
	else if (file_exists(base_path() . $path = assets_directory(Request::path()) . $file)) $urlRaw = $path;
	else if (file_exists(base_path() . $path = APP_ICONS_DIR . $file)) $urlRaw = $path;
	else $urlRaw = ICONS_DIR . $file;

	return $urlRaw;
}

function icon_embed(string $file, array $customClasses = []) {
	$classes = implode(' ', ['icon-embed', ...$customClasses]);

	$url = base_path() . icon_url_raw($file);
	$icon = file_get_contents($url);
	$icon = str_replace('<svg', '<svg class="' . $classes . '"', $icon);

	return $icon;
}
