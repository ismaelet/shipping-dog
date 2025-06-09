<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RecoverController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetController;
use Illuminate\Support\Facades\Route;

/**
 * Login routes
 */
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'auth'])->middleware('guest')->name('login.auth');

/**
 * Registration routes
 */
Route::get('/register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.store');

/**
 * Logout routes
 */
Route::get('/logout', [LogoutController::class, 'index'])->name('logout')->middleware('auth');
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth');

/**
 * Recover and reset password routes
 */
Route::get('/forgot-password', [RecoverController::class, 'index'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [RecoverController::class, 'store'])->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', [ResetController::class, 'index'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetController::class, 'update'])->middleware('guest')->name('password.update');
