<?php

namespace App\Service;

use App\Entity\Search;
use App\ValueObject\SearchResult;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SearchService
{
    public function __construct(private readonly HttpClientInterface $searchServiceClient)
    {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function search(Search $search): SearchResult
    {
        $query =
            [
                'query' => $search->getTerm(),
                'size' => $search->getSize(),
                'page' => $search->getPage(),
            ];
        if (empty($search->getFacets()) === false) {
            $query['features'] = json_encode($search->getFacets());
        }
        if (empty($search->getProductGroup()) === false) {
            $query['warengruppe'] = $search->getProductGroup();
        }
        if (empty($search->getManufacturer()) === false) {
            $query['manufacturers'] = implode(',', $search->getManufacturer());
        }
        $response = $this
            ->searchServiceClient
            ->request(
                'GET',
                'search',
                [
                    'query' => $query,
                ]
            );
        $response = json_decode($response->getContent(), true);
        if ($response['success'] !== true) {
            throw new Exception($response['message']);
        }

        return ResultMapper::createSearchResult($response['content']);
    }
}