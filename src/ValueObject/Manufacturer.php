<?php

namespace App\ValueObject;

class Manufacturer
{
public function __construct(
    private readonly int $count,
    private readonly string $name,
)
{
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