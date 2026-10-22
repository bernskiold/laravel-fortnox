<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources\Filters;

use Carbon\Carbon;

trait FiltersFinancialYear
{
    public function financialYear(int $year): static
    {
        $this->query['financialyear'] = $year;

        return $this;
    }

    public function financialYearFromDate(Carbon $date): static
    {
        $this->query['financialyeardate'] = $date->format('Y-m-d');

        return $this;
    }
}
