<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources;

trait Sortable
{
    public function sortBy(string $field, string $order = 'asc'): static
    {
        $this->query['sortby'] = $field;
        $this->query['sortorder'] = $order === 'asc' ? 'ascending' : 'descending';

        return $this;
    }

    public function sortByDesc(string $field): static
    {
        $this->sortBy($field, 'desc');

        return $this;
    }
}
