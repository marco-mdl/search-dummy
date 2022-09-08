<?php

namespace App\ValueObject;

use IteratorAggregate;
use Traversable;

class ProductGroupCollection implements IteratorAggregate
{
    /** @var array<int, Facet> */
    private array $productGroups = [];

    public function add(ProductGroup $productGroup): void
    {
        $this->productGroups[] = $productGroup;
    }

    public function getIterator(): Traversable
    {
        yield from $this->productGroups;
    }

    public function getProductGroups(): array
    {
        return $this->productGroups;
    }
}