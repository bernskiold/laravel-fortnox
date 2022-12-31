<?php

namespace BernskioldMedia\Fortnox\Contracts\Resources\Search;

use Carbon\Carbon;

trait SearchesCostCenter
{
    public function costCenter(string $costCenter): static
    {
        return $this->search('CostCenter', $costCenter);
    }

}
