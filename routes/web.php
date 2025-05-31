<?php

use BernskioldMedia\Fortnox\Controllers\FortnoxAuthController;

Route::get(config('fortnox.routes.oauth.redirect'), [FortnoxAuthController::class, 'toFortnox'])->name('fortnox.oauth.redirect');
Route::get(config('fortnox.routes.oauth.callback'), [FortnoxAuthController::class, 'handleCallback'])->name('fortnox.oauth.callback');
