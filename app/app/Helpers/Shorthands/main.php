<?php

use App\Helpers\App;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;

/**
 * Check if the user is an admin
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
 * Get the path to a file in the public storage disk
 */
function storage_asset(string $path): string {
    return FileHelper::storageAsset($path);
}
