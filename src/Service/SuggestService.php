<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SuggestService
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
    public function suggest(string $query): array
    {
        $response = $this
            ->searchServiceClient
            ->request(
                'GET',
                'suggest',
                [
                    'query' =>
                        [
                            'query' => $query,
                        ],
                ]
            );
        $response = json_decode($response->getContent(), true);

        if ($response['success'] !== true) {
            throw new Exception($response['message']);
        }

        return $response['content'];
    }
}