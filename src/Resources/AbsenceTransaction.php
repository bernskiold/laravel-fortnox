<?php

namespace BernskioldMedia\Fortnox\Resources;

use BernskioldMedia\Fortnox\Contracts\Resources\Crud\Createable;
use BernskioldMedia\Fortnox\Contracts\Resources\Crud\Deleteable;
use BernskioldMedia\Fortnox\Contracts\Resources\Crud\Readable;
use BernskioldMedia\Fortnox\Contracts\Resources\Crud\Updateable;
use BernskioldMedia\Fortnox\Responses\ListResponse;
use Carbon\Carbon;

class AbsenceTransaction extends BaseResource
{
    use Readable;
    use Createable;
    use Updateable;
    use Deleteable;

    public function employee(int|string $id): static
    {
        return $this->search('employeeid', $id);
    }

    public function date(Carbon $date): static
    {
        return $this->search('date', $date->format('Y-m-d'));
    }

    public function getFor(string|int $employeeId, Carbon $date, string $code): ListResponse
    {
        $formattedDate = $date->format('Y-m-d');
        $raw = $this->client->get($this->getEndpoint()."/$employeeId/$formattedDate/$code");

        return new ListResponse($raw, $this->getPluralKey());
    }

    protected function getEndpoint(): string
    {
        return 'absencetransactions';
    }
}
