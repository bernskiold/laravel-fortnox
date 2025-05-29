<?php

use BernskioldMedia\Fortnox\Controllers\FortnoxAuthController;

Route::get(config('fortnox.routes.oauth.redirect'), [FortnoxAuthController::class, 'toFortnox']);
Route::get(config('fortnox.routes.oauth.callback'), [FortnoxAuthController::class, 'handleCallback']);
