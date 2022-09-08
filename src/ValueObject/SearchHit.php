<?php

namespace App\ValueObject;

class SearchHit
{
    public function __construct(
        private readonly ?array  $features,
        private readonly ?string $ean,
        private readonly ?string $assortment,
        private readonly ?string $name,
        private readonly ?string $supplierArticleNumber,
        private readonly ?string $longtext,
        private readonly ?string $image,
        private readonly ?string $articleNumber,
        private readonly ?string $manufacturer
    )
    {
    }

    public function getFeatures(): array
    {
        return $this->features ?: [];
    }

    public function getEan(): string
    {
        return $this->ean;
    }

    public function getAssortment(): string
    {
        return $this->assortment;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSupplierArticleNumber(): string
    {
        return $this->supplierArticleNumber;
    }

    public function getLongtext(): string
    {
        return $this->longtext;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function getArticleNumber(): string
    {
        return $this->articleNumber;
    }
}