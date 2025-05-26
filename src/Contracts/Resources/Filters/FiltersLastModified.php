<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources\Filters;

use Carbon\Carbon;

trait FiltersLastModified
{
    public function lastModified(Carbon $date): static
    {
        $this->query['lastmodified'] = $date->format('Y-m-d H:i');

        return $this;
    }
}
