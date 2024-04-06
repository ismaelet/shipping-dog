<?php

use App\Http\Controllers\Admin\HomeController;
use App\Models\Admin\AdminSection;
use Illuminate\Support\Facades\Route;

/**
 * Admin routes
 */
Route::prefix('admin')->middleware('admin')->namespace('Admin')->group(function () {
	// Admin dashboard home
	Route::get('/', [HomeController::class, 'index'])->name('admin');
	Route::post('/', [HomeController::class, 'update']);
});


	// Dynamic routes for admin sections
	// $sections = AdminSection::all();

	// foreach ($sections as $section) {
	// 	if ($section->new) {
	// 		$sectionPath = $section->category . '/' . $section->slug;
	// 		$sectionName = 'admin' . $section->category . '.' . str_replace('/', '.', $section->slug);
	// 		$controllerName = 'App\Http\Controllers\Admin\\' . ucfirst($section->category) . '\\' . ucfirst($section->slug) . 'Controller';

	// 		Route::get('/' . $sectionPath, [get_class(new $controllerName), 'index'])->name($sectionName);
	// 		Route::post('/' . $sectionPath, [get_class(new $controllerName), 'store']);
	// 	}
	// }