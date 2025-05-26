<?php

namespace BernskioldMedia\Fortnox;

use BernskioldMedia\Fortnox\Exceptions\OAuth\InvalidAuthorizationCodeException;
use BernskioldMedia\Fortnox\Exceptions\OAuth\InvalidStateException;
use BernskioldMedia\Fortnox\Exceptions\OAuth\TokenRequestException;
use BernskioldMedia\Fortnox\Resources\AbsenceTransaction;
use BernskioldMedia\Fortnox\Resources\Account;
use BernskioldMedia\Fortnox\Resources\AccountChart;
use BernskioldMedia\Fortnox\Resources\Contract;
use BernskioldMedia\Fortnox\Resources\Customer;
use BernskioldMedia\Fortnox\Resources\FinancialYear;
use BernskioldMedia\Fortnox\Resources\Invoice;
use BernskioldMedia\Fortnox\Resources\Project;
use BernskioldMedia\Fortnox\Resources\Sie;
use BernskioldMedia\Fortnox\Resources\Supplier;
use BernskioldMedia\Fortnox\Resources\SupplierInvoice;
use BernskioldMedia\Fortnox\Resources\SupplierInvoicePayment;
use BernskioldMedia\Fortnox\Resources\Voucher;

class Fortnox
{
    public function __construct(
        protected FortnoxClient $client
    ) {
    }

    /**
     * Set the tenant ID for OAuth2 authentication.
     *
     * @param string $tenantId
     * @return $this
     * @throws TokenRequestException
     */
    public function forTenant(string $tenantId): self
    {
        $this->client->forTenant($tenantId);

        return $this;
    }

    /**
     * Get the authorization URL for the OAuth2 flow.
     *
     * @param string|null $state
     * @param string|null $scope
     * @param array $additionalParams
     * @return string
     */
    public function getAuthorizationUrl(?string $state = null, ?string $scope = null, array $additionalParams = []): string
    {
        return $this->client->oauth()->getAuthorizationUrl($state, $scope, $additionalParams);
    }

    /**
     * Exchange an authorization code for an access token.
     *
     * @param string $code
     * @param string $state
     * @param string $expectedState
     * @param string $tenantId
     * @return array
     * @throws InvalidAuthorizationCodeException
     * @throws InvalidStateException
     * @throws TokenRequestException
     */
    public function exchangeAuthorizationCode(
        string $code,
        string $state,
        string $expectedState,
        string $tenantId
    ): array {
        return $this->client->oauth()->exchangeAuthorizationCode($code, $state, $expectedState, $tenantId);
    }

    /**
     * Refresh an access token using a refresh token.
     *
     * @param string $tenantId
     * @return array
     * @throws TokenRequestException
     */
    public function refreshToken(string $tenantId): array
    {
        return $this->client->oauth()->refreshToken($tenantId);
    }

    /**
     * Check if a token exists for a specific tenant.
     *
     * @param string $tenantId
     * @return bool
     */
    public function hasToken(string $tenantId): bool
    {
        return $this->client->oauth()->hasToken($tenantId);
    }

    /**
     * Delete the token for a specific tenant.
     *
     * @param string $tenantId
     * @return void
     */
    public function deleteToken(string $tenantId): void
    {
        $this->client->oauth()->deleteToken($tenantId);
    }

    public function absenceTransactions(): AbsenceTransaction
    {
        return new AbsenceTransaction($this->client);
    }

    public function accounts(): Account
    {
        return new Account($this->client);
    }

    public function accountCharts(): AccountChart
    {
        return new AccountChart($this->client);
    }

    public function contracts(): Contract
    {
        return new Contract($this->client);
    }

    public function customers(): Customer
    {
        return new Customer($this->client);
    }

    public function financialYears(): FinancialYear
    {
        return new FinancialYear($this->client);
    }

    public function invoices(): Invoice
    {
        return new Invoice($this->client);
    }

    public function project(): Project
    {
        return new Project($this->client);
    }

    public function sie(): Sie
    {
        return new Sie($this->client);
    }

    public function suppliers(): Supplier
    {
        return new Supplier($this->client);
    }

    public function supplierInvoices(): SupplierInvoice
    {
        return new SupplierInvoice($this->client);
    }

    public function supplierInvoicePayments(): SupplierInvoicePayment
    {
        return new SupplierInvoicePayment($this->client);
    }

    public function vouchers(): Voucher
    {
        return new Voucher($this->client);
    }
}
