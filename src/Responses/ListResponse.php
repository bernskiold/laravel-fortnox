<?php

namespace BernskioldMedia\Fortnox\Responses;

use Illuminate\Support\Collection;

class ListResponse
{
    public int $totalRecords;
    public int $totalPages;
    public int $currentPage;
    protected Collection $data;

    public function __construct(object $raw, string $dataKey)
    {
        $this->totalRecords = $raw->MetaInformation->{"@TotalResources"};
        $this->totalPages = $raw->MetaInformation->{"@TotalPages"};
        $this->currentPage = $raw->MetaInformation->{"@CurrentPage"};
        $this->data = collect($raw->{$dataKey});
    }

    public function hasMore(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    public function get(): Collection
    {
        return $this->data;
    }
}
