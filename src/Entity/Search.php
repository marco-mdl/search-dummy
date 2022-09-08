<?php

namespace App\Entity;

class Search
{
    private ?string $excludedAssortments = null;

    private ?string $term = null;

    private int $size = 25;

    private int $page = 1;
    private ?string $productGroup = null;

    private ?array $facets = [];

    public function getExcludedAssortments(): ?string
    {
        return $this->excludedAssortments;
    }

    public function setExcludedAssortments(?string $excludedAssortments): self
    {
        $this->excludedAssortments = $excludedAssortments;

        return $this;
    }

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(?string $term): self
    {
        $this->term = $term;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): void
    {
        if ($size !== null) {
            $this->size = $size;
        }
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(?int $page): void
    {
        if ($page !== null) {
            $this->page = $page;
        }
    }

    public function getFacets(): ?array
    {
        return $this->facets;
    }

    public function setFacets(?array $facets): void
    {
        $this->facets = $facets;
    }

    public function getProductGroup(): ?string
    {
        return $this->productGroup;
    }

    public function setProductGroup(?string $productGroup): void
    {
        $this->productGroup = $productGroup;
    }
}
