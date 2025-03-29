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

// /**
//  * Activate the seller with ID 7083 on the first Saturday of the month
//  */
// Schedule::call(fn () => Seller::find(7083)->update(['status' => 1]))
// 	->daily()
// 	->when(fn () => now() == now()->firstOfMonth()->next('Saturday'))
// 	->environments(['staging', 'production']);

// /**
//  * Deactivate the seller with ID 7083 on the following Monday
//  */
// Schedule::call(fn () => Seller::find(7083)->update(['status' => 0]))
// 	->daily()
// 	->when(fn () => now() == now()->firstOfMonth()->next('Saturday')->next('Monday'))
// 	->environments(['staging', 'production']);
