<?php

namespace App\Service;

use App\ValueObject\Facet;
use App\ValueObject\FacetCollection;
use App\ValueObject\Manufacturer;
use App\ValueObject\ManufacturerCollection;
use App\ValueObject\ProductGroup;
use App\ValueObject\ProductGroupCollection;
use App\ValueObject\SearchHit;
use App\ValueObject\SearchHitCollection;
use App\ValueObject\SearchResult;

class ResultMapper
{
    public static function createSearchResult(array $response): SearchResult
    {
        $hitCollection = new SearchHitCollection();
        foreach ($response['hits'] as $hit) {
            $hit = $hit['data'];
            $hitCollection
                ->add(
                    new SearchHit(
                        features: $hit['features'],
                        ean: $hit['ean'],
                        assortment: $hit['assortment'],
                        name: $hit['name'],
                        supplierArticleNumber: $hit['supplier_artnr'],
                        longtext: $hit['longtext_storeable'],
                        image: $hit['images_main'],
                        articleNumber: $hit['artnr'],
                        manufacturer: $hit['manufacturer'],
                    )
                );
        }
        $facetCollection = new FacetCollection();
        $productGroupCollection = new ProductGroupCollection();
        $manufacturerCollection = new ManufacturerCollection();
        foreach ($response['facets'] as $name => $facets) {
            if ($name === 'warengruppen') {
                foreach ($facets as $id => $group) {
                    $productGroupCollection
                        ->add(
                            new ProductGroup(
                                id: $id,
                                count: $group['count'],
                                name: $group['name']
                            )
                        );
                }
                continue;
            }
            if ($name === 'manufacturer') {
                foreach ($facets as $manufacturerName => $count) {
                    $manufacturerCollection
                        ->add(
                            new Manufacturer(
                                count: $count,
                                name: $manufacturerName
                            )
                        );
                }
                continue;
            }

            $facetCollection
                ->add(
                    new Facet(
                        name: $name,
                        values: $facets,
                    )
                );
        }

        return new SearchResult(
            count: $response['count'],
            size: $response['size'],
            page: $response['page'],
            totalCount: $response['total_count'],
            searchHitCollection: $hitCollection,
            facetCollection: $facetCollection,
            productGroupCollection: $productGroupCollection,
            manufacturerCollection: $manufacturerCollection
        );
    }
}