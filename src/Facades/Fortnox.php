<?php

namespace BernskioldMedia\Fortnox\Facades;

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
use Illuminate\Support\Facades\Facade;

/**
 * @method static AbsenceTransaction absenceTransactions()
 * @method static Account accounts()
 * @method static AccountChart accountCharts()
 * @method static Contract contracts()
 * @method static Customer customers()
 * @method static FinancialYear financialYears()
 * @method static Invoice invoices()
 * @method static Project project()
 * @method static Sie sie()
 * @method static Supplier supplier()
 * @method static SupplierInvoice supplierInvoices()
 * @method static SupplierInvoicePayment supplierInvoicePayments()
 * @method static Voucher vouchers()
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
