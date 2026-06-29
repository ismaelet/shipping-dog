<?php

use App\Helpers\App;
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
