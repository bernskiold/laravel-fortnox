<?php

namespace BernskioldMedia\Fortnox\Resources;

use BernskioldMedia\Fortnox\Contracts\Resources\Crud\Createable;
use BernskioldMedia\Fortnox\Contracts\Resources\Crud\Readable;
use BernskioldMedia\Fortnox\Contracts\Resources\Crud\Updateable;
use BernskioldMedia\Fortnox\Contracts\Resources\Search\SearchesCostCenter;

class Voucher extends BaseResource
{
    use Readable;
    use Createable;
    use Updateable;
    use SearchesCostCenter;

    protected function getEndpoint(): string
    {
        return 'vouchers';
    }
}
