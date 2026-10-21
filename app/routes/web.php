<?php

use App\Http\Controllers\Community;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EmergencyEmail;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OpenShopController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\SaleTermsController;
use App\Http\Controllers\UploadTermsController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\NotSeller;
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
 * Home
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * Community
 */
Route::get('/community', [Community::class, 'index'])->name('community');

/**
 * Open shop
 */
Route::get('/open-shop', [OpenShopController::class, 'index'])->middleware([NotSeller::class, Authenticate::class])->name('open-shop');
Route::post('/open-shop', [OpenShopController::class, 'add'])->middleware([NotSeller::class, Authenticate::class])->name('open-shop');

/**
 * Support
 */
Route::get('/contacts', [ContactsController::class, 'index'])->name('contacts');
Route::post('/contacts', [ContactsController::class, 'send'])->name('contacts');
Route::get('/support/faq', [FaqController::class, 'index'])->name('support.faq');
Route::get('/support/sale-terms', [SaleTermsController::class, 'index'])->name('support.sale-terms');
Route::get('/support/upload-terms', [UploadTermsController::class, 'index'])->name('support.upload-terms');
Route::get('/support/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('support.privacy-policy');

/**
 * Downloads
 */
Route::get('/download/{filename}', [DownloadController::class, 'download'])->name('download')->where('filename', '.*');

/**
 * Utility routes
 */
Route::get('/emergency-email', [EmergencyEmail::class, 'sendEmail'])->middleware(Admin::class);
