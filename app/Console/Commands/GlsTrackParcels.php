<?php

namespace App\Console\Commands;

use App\Services\GlsService;
use Illuminate\Console\Command;

class GlsTrackParcels extends Command {

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
	public function handle() {
		$glsService = new GlsService();
		$glsService->trackParcels();
	}
}
