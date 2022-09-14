<?php

namespace App\Service;

use App\ValueObject\IndexingResult;
use Exception;
use SplFileInfo;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IndexingService
{
    public function __construct(private readonly HttpClientInterface $elasticClient)
    {
    }

    public function indexingFiles(iterable $files, string $index): IndexingResult
    {
        $indexingResult = new IndexingResult();
        foreach ($files as $file) {
            try {
                $result = $this->indexingFile($file, $index);
                $this
                    ->handleResponse($result, $indexingResult);
            } catch (Exception $exception) {
                var_dump($exception->getMessage());
            }
        }

        return $indexingResult;
    }

    private function handleResponse(array $response, IndexingResult $indexingResult): void
    {
        if ($response['errors'] === false) {
            $indexingResult
                ->addIndexedDocuments(count($response['items']));

            return;
        }

        $indexedDocuments = 0;
        foreach ($response['items'] as $item) {
            if (isset($item['index']['error'])) {
                $indexingResult->addError($item['index']);
            } else {
                $indexedDocuments++;
            }
        }

        $indexingResult
            ->addIndexedDocuments($indexedDocuments);

    }

    private function indexingFile(SplFileInfo $file, string $index): array
    {
        $content = file_get_contents($file->getRealPath());
        $content = str_replace('"_index":"test"', '"_index":"' . $index . '"', $content);
        $content = json_decode($content, true);

        $content = $content['body'];
        $data = '';
        $i = 0;
        foreach ($content as $row) {
            $i++;
            if(isset($row['index']['_index'])){
                $row['index']['_index'] = $index;
            }
            $data .= json_encode($row) . "\n";
        }

        return $this
            ->postDocuments($data);
    }

    private function postDocuments(string $documents): array
    {
        $response = $this
            ->elasticClient
            ->request(
                'POST',
                '_bulk',
                [
                    'body' => $documents,
                ]
            );

        return json_decode($response->getContent(), true);
    }
}