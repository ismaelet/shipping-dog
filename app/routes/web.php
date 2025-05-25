<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\CustomerPicsController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\Ecommerce\DesignController;
use App\Http\Controllers\EmergencyEmail;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Seller;
use App\Models\Ecommerce\Seller as EcommerceSeller;
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
 * * Home
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * * Support
 */
Route::get('/contacts', [ContactsController::class, 'index'])->name('contacts');
Route::get('/infos/faq', [FaqController::class, 'index'])->name('infos.faq');

/**
 * * Designs
 */
// Create a design
Route::resource('designs', DesignController::class)->only(['index', 'store', 'edit', 'destroy']);
Route::post('/designs/{design}/upload-image', [DesignController::class, 'uploadImage'])->name('designs.upload-image');
Route::post('/designs/{design}/update-tags', [DesignController::class, 'updateTags'])->name('designs.update-tags');
Route::post('/designs/{design}/update-background-color', [DesignController::class, 'updateBackgroundColor'])->name('designs.update-background-color');
Route::post('/designs/{design}/update-default-color', [DesignController::class, 'updateDefaultColor'])->name('designs.update-default-color');

/**
 * * Downloads
 */
Route::get('/download/{filename}', [DownloadController::class, 'download'])->name('download')->where('filename', '.*');

/**
 * * Misc
 */
Route::get('/customer-pics', [CustomerPicsController::class, 'index'])->name('customer-pics');

Route::get('/emergency-email', [EmergencyEmail::class, 'sendEmail'])->middleware(Admin::class);

Route::get('/seller-email-list', function () {

	$ignoredEmails = [
		'andrea_trentini94@ya.it',
		'valeriosiciliano@email.it',
		'temp@gmail.com',
		'inseriscilatuaemail@gmail.it',
		'noncancellareisma@hotmail.it',
		'oscarufficiale@mail.com',
		'weareawesome@panicpanik.com',
		'yourmail@gmail.com',
		'ssss@gmail.com',
		'quellidelcalciomercato@aol.com',
		'ciriii@hotmail.kt',
		'mettile@ditanelnaso.com',
		'giorgiamiranda@yahoo.it',
		'danieletomoiaga@yahoo.it',
		'greco4581@outlook.it',
		'attilioguerra@gmail.com',
		'giorgiodefrancesco71.1@gmail.com',
		'fugo.pannacotta@tiscali.it',
		'admin@mondoduster.it',
		'aaa@aaa.com',
		'ernestotommasocampagnuolo2002@gmail.com',
		'tizianoconta@tiscali.it',
		'giorgiodefrancesco71.1@gmail.com',
		'admin@mondoduster.it',
		'fiel89@hotmail.it',
	];

	$sellers = EcommerceSeller::join('users', 'users.id', '=', 'stores.user_id')
		->where('stores.status', true)
		->get();

	$emails = $sellers->pluck('email')->toArray();
	$emails = array_chunk($emails, 50);

	foreach ($emails as $key => $chunk) {
		echo '<h3>Gruppo ' . ($key + 1) . '</h3>';

		foreach ($chunk as $email) {
			if (!in_array($email, $ignoredEmails)) {
				echo $email . '<br>';
			}
		}

		echo '<br>';
	}
})->middleware(Admin::class);

/**
 * * Legacy routes
 */
// Route::any('/order/status', LegacyController::class)->name('order_items.status');
// Route::any('{path}', LegacyController::class)->where('path', '.*');
