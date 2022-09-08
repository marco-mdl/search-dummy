<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UrlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('urlDecode', [$this, 'urlDecode']),
        ];
    }

    public function urlDecode(string $value): string
    {
        return urldecode($value);
    }
}
