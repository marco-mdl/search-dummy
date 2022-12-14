<?php

namespace App\Twig;

use App\Entity\Search;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('facetIsSubmitted', [$this, 'facetIsSubmitted']),
            new TwigFunction('manufacturerIsSubmitted', [$this, 'manufacturerIsSubmitted']),
        ];
    }

    public function facetIsSubmitted(mixed $value, string $facetName, Search $search): bool
    {
        $facets = $search->getFacets();
        if (key_exists($facetName, $facets)) {
            return in_array($value, $facets[$facetName]);
        }

        return false;
    }

    public function manufacturerIsSubmitted(mixed $value, Search $search): bool
    {
        return $search->getManufacturer() && in_array($value, $search->getManufacturer());
    }
}
