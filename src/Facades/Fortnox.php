<?php

namespace BernskioldMedia\Fortnox\Facades;

use BernskioldMedia\Fortnox\Resources\AbsenceTransaction;
use BernskioldMedia\Fortnox\Resources\Account;
use BernskioldMedia\Fortnox\Resources\AccountChart;
use BernskioldMedia\Fortnox\Resources\Contract;
use BernskioldMedia\Fortnox\Resources\Customer;
use BernskioldMedia\Fortnox\Resources\FinancialYear;
use BernskioldMedia\Fortnox\Resources\Invoice;
use BernskioldMedia\Fortnox\Resources\Sie;
use BernskioldMedia\Fortnox\Resources\SupplierInvoice;
use BernskioldMedia\Fortnox\Resources\SupplierInvoicePayment;
use Illuminate\Support\Facades\Facade;

/**
 * @method static AbsenceTransaction absenceTransactions()
 * @method static Account accounts()
 * @method static AccountChart accountCharts()
 * @method static Contract contracts()
 * @method static Customer customers()
 * @method static FinancialYear financialYears()
 * @method static Invoice invoices()
 * @method static Sie sie()
 * @method static SupplierInvoice supplierInvoices()
 * @method static SupplierInvoicePayment supplierInvoicePayments()
 *
 * @see \BernskioldMedia\Fortnox\Fortnox
 */
class Fortnox extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-fortnox';
    }
}
