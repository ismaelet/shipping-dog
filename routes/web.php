<?php

use App\Http\Controllers\LegacyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Admin routes
 */
if (file_exists(__DIR__ . '/admin.php')) include_once __DIR__ . '/admin.php';

/**
 * Legacy routes
 */
Route::any('{path}', LegacyController::class)->where('path', '.*');
