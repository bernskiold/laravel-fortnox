<?php

namespace BernskioldMedia\Fortnox\Controllers;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\Token;
use Laravel\Socialite\Two\User;
use function app;
use function config;
use function dd;
use function explode;
use function hash_equals;
use function is_null;
use function redirect;
use function str;
use function url;

class FortnoxAuthController
{

    public function toFortnox(Request $request)
    {
        $request->session()->put('fortnox.oauth.request_url', url()->previous());

        $parameters = [
            'access_type' => 'offline',
        ];

        if (config('fortnox.use_service_account', false)) {
            $parameters['account_type'] = 'service';
        }

        return Socialite::driver('fortnox')
            ->with($parameters)
            ->scopes(explode(',', config('fortnox.scopes', '')))
            ->redirect();
    }

    public function handleCallback(Request $request)
    {
        $requestUrl = $request->session()->pull('fortnox.oauth.request_url');

        if ($request->has('error')) {
            return redirect()->to($requestUrl)
                ->with('type', 'fortnox')
                ->with('status', 'error')
                ->with('message', $request->input('error_description', 'An error occurred during the Fortnox authentication process.'));
        }

        try {
            /**
             * @var Token $token
             */
            $token = Socialite::driver('fortnox')->token();
        } catch (\Exception $e) {
            return redirect()->to($requestUrl)
                ->with('type', 'fortnox')
                ->with('status', 'error')
                ->with('message', 'Failed to authenticate with Fortnox: ' . $e->getMessage());
        }

        /**
         * Store the token using the configured storage provider.
         *
         * @var TokenStorage $storageProvider
         */
        $storageProvider = app(config('fortnox.storage_provider'));
        $storageProvider->storeToken($token);

        return redirect()->to($requestUrl)
            ->with('type', 'fortnox')
            ->with('status', 'success')
            ->with('message', 'Successfully authenticated with Fortnox.');
    }
}
