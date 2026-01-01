<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources\Filters;

use BernskioldMedia\Fortnox\Exceptions\InvalidParameter;

trait HasPagination
{
    public function perPage(int $amount): static
    {
        if ($amount > 500) {
            throw InvalidParameter::tooManyPerPage();
        }

        $this->query['limit'] = $amount;

        return $this;
    }

    public function offset(int $offset): static
    {
        $this->query['offset'] = $offset;

        return $this;
    }

    public function page(int $page): static
    {
        $this->query['page'] = $page;

        return $this;
    }
}
