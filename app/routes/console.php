
<?php

use App\Console\Commands\GlsTrackParcels;
use App\Console\Commands\UpdateSaleCount;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Update the recent sale count daily at 02:00
Schedule::call(fn () => Artisan::call(UpdateSaleCount::class))
    ->dailyAt('02:00')
    ->environments(['staging', 'production']);

// Track all parcels daily at 19:00
Schedule::call(fn () => Artisan::call(GlsTrackParcels::class))
    ->dailyAt('19:00')
    ->environments(['staging', 'production']);

// Clear expired password reset tokens daily
Schedule::call(fn () => Artisan::call('auth:clear-resets'))
    ->daily();

// Durable payment scan also recovers a process failure after callback commit.
Schedule::command('nexi:process')->everyMinute()->withoutOverlapping();
