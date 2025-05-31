<?php

namespace BernskioldMedia\Fortnox\Controllers;

use BernskioldMedia\Fortnox\Contracts\TokenStorage;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use function hash_equals;

class FortnoxAuthController
{

    public function toFortnox(Request $request)
    {
        $request->session()->put('fortnox.oauth_state', str()->random(40));

        return Socialite::driver('fortnox')
            ->with(['state' => session('fortnox.oauth_state')])
            ->scopes(config('fortnox.scopes', []))
            ->redirect();
    }

    public function handleCallback(Request $request)
    {
        $receivedState = $request->input('state');
        $expectedState = $request->session()->pull('fortnox.oauth_state');

        if (!$receivedState || !$expectedState || !hash_equals($receivedState, $expectedState)) {
            return redirect()->back()
                ->with('type', 'fortnox')
                ->with('status', 'error')
                ->with('message', 'Invalid state parameter received from Fortnox.');
        }

        try {
            /**
             * @var User $fortnoxUser
             */
            $fortnoxUser = Socialite::driver('fortnox')->user();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('type', 'fortnox')
                ->with('status', 'error')
                ->with('message', 'Failed to authenticate with Fortnox: ' . $e->getMessage());
        }

        if (!$fortnoxUser->token) {
            return redirect()->back()
                ->with('type', 'fortnox')
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

        $redirectUrl = config('fortnox.routes.success_redirect_route');

        if (is_null($redirectUrl)) {
            return redirect()->back()
                ->with('type', 'fortnox')
                ->with('status', 'success')
                ->with('message', 'Successfully authenticated with Fortnox.');
        }

        return redirect()->to($redirectUrl)
            ->with('type', 'fortnox')
            ->with('status', 'success')
            ->with('message', 'Successfully authenticated with Fortnox.');
    }
}
