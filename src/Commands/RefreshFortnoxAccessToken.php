<?php

namespace BernskioldMedia\Fortnox\Commands;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use Illuminate\Console\Command;
use function app;
use function config;

class RefreshFortnoxAccessToken extends Command
{

    protected $signature = 'fortnox:refresh-access-token
                            {--force : Force the refresh of the access token, even if it is not expired.}';

    protected $description = 'Refresh the Fortnox access token.';

    public function handle()
    {
        /**
         * @var TokenStorage $storageProvider
         */
        $storageProvider = app(config('fortnox.storage_provider'));

        if (!$storageProvider->hasToken()) {
            $this->error('No access token found. Please authenticate with Fortnox first.');
            return self::FAILURE;
        }
    }

}
