<?php

use App\Console\Commands\GlsTrackParcels;
use App\Console\Commands\UpdateSaleCount;
use App\Models\Ecommerce\Seller;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

/**
 * Update the recent sale count daily at 02:00
 */
Schedule::command(UpdateSaleCount::class)
	->dailyAt('02:00')
	->environments(['staging', 'production']);

/**
 * Track all parcels daily at 19:00
 */
Schedule::command(GlsTrackParcels::class)
	->dailyAt('19:00')
	->environments(['staging', 'production']);


Schedule::command('auth:clear-resets')->daily();
