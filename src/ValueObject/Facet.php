<?php

namespace App\ValueObject;


class Facet
{
    public function __construct(
        private readonly string $name,
        private readonly array  $values,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}