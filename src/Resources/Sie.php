<?php

namespace BernskioldMedia\Fortnox\Resources;

use sie\Parser;

class Sie extends BaseResource
{
    protected int $type = 1;

    public function fileType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function yearBalance(): static
    {
        return $this->fileType(1);
    }

    public function periodBalance(): static
    {
        return $this->fileType(2);
    }

    public function objectBalance(): static
    {
        return $this->fileType(3);
    }

    public function transactions(): static
    {
        return $this->fileType(4);
    }

    public function raw(?int $type = null): string
    {
        $type = $type ?? $this->type;

        return $this->client->contents($this->getEndpoint()."/$type", $this->query);
    }

    public function get(?int $type = null): object
    {
        return (new Parser())->parseSieFileContents($this->raw($type));
    }

    protected function getEndpoint(): string
    {
        return 'sie';
    }
}
