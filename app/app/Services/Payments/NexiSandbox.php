<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class NexiSandbox
{
    public function assertIsolated(): void
    {
        if (! app()->environment('local')
            || ! config('services.nexi.local_sandbox')
            || ! config('services.nexi.sandbox')
            || DB::connection()->getDatabaseName() !== 'shipping_dog_nexi_sandbox') {
            throw new RuntimeException('This action requires the isolated local Nexi sandbox database.');
        }
    }
}
