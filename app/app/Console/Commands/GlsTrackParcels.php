<?php

namespace App\Console\Commands;

use App\Services\GlsService;
use Illuminate\Console\Command;

class GlsTrackParcels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gls:track-parcels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Track parcels
     */
    public function handle(): int
    {
        $this->info('1. GLS command started');

        $glsService = new GlsService;

        $this->info('2. Calling trackParcels()');

        $glsService->trackParcels();

        $this->info('3. GLS command completed');

        return self::SUCCESS;
    }
}
