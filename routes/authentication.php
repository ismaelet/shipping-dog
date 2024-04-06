<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RecoverController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetController;
use Illuminate\Support\Facades\Route;

/**
 * Auth routes
 */
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'auth'])->middleware('guest');

Route::get('/register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');

Route::get('/logout', [LogoutController::class, 'index'])->name('logout')->middleware('auth');
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth');

Route::get('/recover', [RecoverController::class, 'index'])->name('auth.recover')->middleware('guest');
Route::post('/recover', [RecoverController::class, 'store'])->middleware('guest');

Route::get('/reset', [ResetController::class, 'index'])->name('auth.reset')->middleware('guest');
Route::post('/reset', [ResetController::class, 'update'])->middleware('guest');
