<?php

namespace BernskioldMedia\Fortnox;

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
    )
    {
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
