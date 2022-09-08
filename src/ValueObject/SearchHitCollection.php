<?php

namespace App\ValueObject;

use IteratorAggregate;
use Traversable;

class SearchHitCollection implements IteratorAggregate
{
    /** @var array<int, SearchHit> */
    private array $parts = [];

    public function add(SearchHit $queryPart): void
    {
        $this->parts[] = $queryPart;
    }

    public function getIterator(): Traversable
    {
        yield from $this->parts;
    }
}