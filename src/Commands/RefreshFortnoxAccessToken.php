<?php

namespace BernskioldMedia\Fortnox\Commands;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use Illuminate\Console\Command;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\Token;
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

        $token = $storageProvider->getToken();

        if (!$token) {
            $this->error('No access token found. Please authenticate with Fortnox first.');
            return self::FAILURE;
        }

        if (now()->lessThan($token->expiresAt) && !$this->option('force')) {
            $this->info('Access token is still valid. No need to refresh. Use with --force to refresh anyway.');
            return self::SUCCESS;
        }

        /**
         * @var Token $newToken
         */
        $newToken = Socialite::driver('fortnox')->refreshToken($token->refreshToken);

        $storageProvider->storeToken($newToken);

        $this->info('Access token refreshed successfully.');

        return self::SUCCESS;
    }

}
