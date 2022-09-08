<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IntegerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('ceil', [$this, 'ceil']),
        ];
    }

    public function ceil(int|float $value): string
    {
        return ceil($value);
    }
}
