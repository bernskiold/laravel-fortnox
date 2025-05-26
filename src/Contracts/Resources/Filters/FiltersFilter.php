<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources\Filters;

trait FiltersFilter
{
    public function filter(string $filter): static
    {
        $this->query['filter'] = $filter;

        return $this;
    }
}
