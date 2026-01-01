<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources\Search;

trait SearchesCostCenter
{
    public function costCenter(string $costCenter): static
    {
        return $this->search('CostCenter', $costCenter);
    }
}
