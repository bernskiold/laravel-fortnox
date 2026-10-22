<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources\Filters;

use Carbon\Carbon;

trait FiltersDates
{
    public function from(Carbon $date): static
    {
        $this->query['fromdate'] = $date->format('Y-m-d');

        return $this;
    }

    public function to(Carbon $date): static
    {
        $this->query['todate'] = $date->format('Y-m-d');

        return $this;
    }
}
