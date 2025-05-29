<?php

namespace BernskioldMedia\Fortnox\Controllers;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class FortnoxAuthController
{

    public function toFortnox(Request $request)
    {
        return Socialite::driver('fortnox')
            ->scopes(config('fortnox.scopes', []))
            ->redirect();
    }

    public function handleCallback(Request $request)
    {
        try {
            /**
             * @var User $fortnoxUser
             */
            $fortnoxUser = Socialite::driver('fortnox')->user();
        } catch (\Exception $e) {
            return redirect()->to(config('fortnox.oauth_redirect_url', '/'))
                ->with('status', 'error')
                ->with('message', 'Failed to authenticate with Fortnox: ' . $e->getMessage());
        }

        if (!$fortnoxUser->token) {
            return redirect()->to(config('fortnox.oauth_redirect_url', '/'))
                ->with('status', 'error')
                ->with('message', 'No access token received from Fortnox.');
        }

        /**
         * Store the token using the configured storage provider.
         *
         * @var TokenStorage $storageProvider
         */
        $storageProvider = app(config('fortnox.storage_provider'));
        $storageProvider->storeToken($fortnoxUser->refreshToken ?? null);
    }

}
