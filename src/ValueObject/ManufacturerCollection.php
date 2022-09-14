<?php

namespace App\ValueObject;

use IteratorAggregate;
use Traversable;

class ManufacturerCollection implements IteratorAggregate
{
    /** @var array<int, Facet> */
    private array $manufacturer = [];

    public function add(Manufacturer $productGroup): void
    {
        $this->manufacturer[] = $productGroup;
    }

    public function getIterator(): Traversable
    {
        yield from $this->manufacturer;
    }

    public function getManufacturer(): array
    {
        return $this->manufacturer;
    }
}