<?php

namespace App\ValueObject;

class ProductGroup
{
public function __construct(
    private readonly string $id,
    private readonly int $count,
    private readonly string $name,
)
{
}
    public function getId(): string
    {
        return $this->id;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getName(): string
    {
        return $this->name;
    }
}