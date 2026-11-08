<?php

namespace App\Console\Commands;

use App\Services\Payments\NexiSandbox;
use App\Services\Payments\NexiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class NexiSandboxPreflight extends Command
{
    protected $signature = 'nexi:sandbox-preflight';

    protected $description = 'Check local Nexi sandbox readiness without contacting Nexi or creating payments';

    public function handle(NexiSandbox $sandbox, NexiService $nexi): int
    {
        $failed = false;
        $check = function (string $label, callable $test) use (&$failed): void {
            try {
                $passed = $test() !== false;
            } catch (Throwable) {
                $passed = false;
            }
            $this->line(($passed ? 'PASS ' : 'FAIL ').$label);
            $failed = $failed || ! $passed;
        };
        $check('Isolated local sandbox database and mode', fn () => $sandbox->assertIsolated());
        $check('Sandbox API key configured', fn () => $nexi->assertConfigured());
        $check('Production API key empty', fn () => ! config('services.nexi.api_key'));
        $check('Application key configured; debug disabled', fn () => config('app.key') && ! config('app.debug'));
        $check('External operations disabled; mail uses log', fn () => ! config('services.nexi.external_operations_enabled')
            && config('mail.default') === 'log');
        $check('GLS credentials empty', fn () => ! config('services.gls.client_id')
            && ! config('services.gls.client_password') && ! config('services.gls.contract_code'));
        $check('Only sandbox tunnel proxy trusted', fn () => config('services.nexi.local_trusted_proxies') === '172.30.89.10');
        $check('Secure file sessions', fn () => config('session.driver') === 'file' && config('session.secure'));
        $check('Public HTTPS callback URLs', function () {
            $root = rtrim((string) config('app.url'), '/');
            $host = parse_url($root, PHP_URL_HOST);
            if (! filter_var($root, FILTER_VALIDATE_URL) || parse_url($root, PHP_URL_SCHEME) !== 'https'
                || ! is_string($host) || ! str_contains($host, '.')
                || in_array($host, ['localhost', '127.0.0.1'], true)) {
                return false;
            }
            foreach (['result', 'cancel', 'notification'] as $route) {
                $url = route('nexi.orders.'.$route, $route === 'notification' ? [] : ['order' => 1]);
                if (! str_starts_with($url, $root.'/')) {
                    return false;
                }
            }

            return true;
        });
        $check('Migrations applied', function () {
            $migrator = app('migrator');
            if (! $migrator->repositoryExists()) {
                return false;
            }

            return array_diff(array_keys($migrator->getMigrationFiles(database_path('migrations'))),
                $migrator->getRepository()->getRan()) === [];
        });
        $check('Nexi tables and REST fields available', fn () => Schema::hasTable('nexi_fulfillment_operations')
            && Schema::hasColumns('nexi_payments', ['provider_order_id', 'security_token', 'initialization_status']));
        $check('Nexi payment method active', fn () => DB::table('payment_methods')->where('id', 2)->where('active', 1)->exists());
        $check('Built assets available; Vite hot URL absent', fn () => is_file(public_path('build/manifest.json'))
            && ! is_file(public_path('hot')));
        $this->comment('No provider request was made. Check tunnel reachability and the running scheduler separately.');

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
