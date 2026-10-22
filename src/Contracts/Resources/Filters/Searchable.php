<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources\Filters;

trait Searchable
{
    public function search(string $field, mixed $value): static
    {
        $this->query[$field] = $value;

        return $this;
    }
}
