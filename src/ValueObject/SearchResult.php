<?php

namespace App\ValueObject;

class SearchResult
{
    public function __construct(
        private readonly int                    $count,
        private readonly int                    $size,
        private readonly int                    $page,
        private readonly int                    $totalCount,
        private readonly SearchHitCollection    $searchHitCollection,
        private readonly FacetCollection        $facetCollection,
        private readonly ProductGroupCollection $productGroupCollection,
    )
    {
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getSearchHitCollection(): SearchHitCollection
    {
        return $this->searchHitCollection;
    }

    public function getFacetCollection(): FacetCollection
    {
        return $this->facetCollection;
    }

    public function getProductGroupCollection(): ProductGroupCollection
    {
        return $this->productGroupCollection;
    }
}