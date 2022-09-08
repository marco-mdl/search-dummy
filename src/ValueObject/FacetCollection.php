<?php

namespace App\ValueObject;

use IteratorAggregate;
use Traversable;

class FacetCollection implements IteratorAggregate
{
    /** @var array<int, Facet> */
    private array $facets = [];

    public function add(Facet $facets): void
    {
        $this->facets[] = $facets;
    }

    public function getIterator(): Traversable
    {
        yield from $this->facets;
    }

    /**
     * @return array
     */
    public function getFacets(): array
    {
        return $this->facets;
    }
}