<?php

use BernskioldMedia\Fortnox\Fortnox;
use BernskioldMedia\Fortnox\FortnoxClient;
use BernskioldMedia\Fortnox\Resources\Account;
use BernskioldMedia\Fortnox\Resources\Customer;
use BernskioldMedia\Fortnox\Resources\Invoice;
use BernskioldMedia\Fortnox\Resources\Project;
use BernskioldMedia\Fortnox\Resources\Supplier;
use BernskioldMedia\Fortnox\Resources\Voucher;

it('can create a fortnox client', function () {
    $client = new FortnoxClient(
        accessToken: 'test-token',
        baseUrl: 'https://api.fortnox.se/3/'
    );

    expect($client)->toBeInstanceOf(FortnoxClient::class);
    expect($client->request)->toBeInstanceOf(\Illuminate\Http\Client\PendingRequest::class);
});

it('can create a fortnox instance with a client', function () {
    $client = new FortnoxClient(
        accessToken: 'test-token',
        baseUrl: 'https://api.fortnox.se/3/'
    );

    $fortnox = new Fortnox($client);

    expect($fortnox)->toBeInstanceOf(Fortnox::class);
});

it('returns correct resource types from fortnox instance', function () {
    $client = new FortnoxClient(
        accessToken: 'test-token',
        baseUrl: 'https://api.fortnox.se/3/'
    );

    $fortnox = new Fortnox($client);

    expect($fortnox->accounts())->toBeInstanceOf(Account::class);
    expect($fortnox->customers())->toBeInstanceOf(Customer::class);
    expect($fortnox->invoices())->toBeInstanceOf(Invoice::class);
    expect($fortnox->project())->toBeInstanceOf(Project::class);
    expect($fortnox->suppliers())->toBeInstanceOf(Supplier::class);
    expect($fortnox->vouchers())->toBeInstanceOf(Voucher::class);
});
