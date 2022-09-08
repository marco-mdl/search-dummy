<?php

namespace App\ValueObject;

class IndexingResult
{
    private int $indexedDocuments = 0;
    private array $errors = [];

    public function addError(array $error): void{
        $this->errors[] = $error;
    }
    public function addIndexedDocuments(int $count): void
    {
        $this->indexedDocuments += $count;
    }

    public function getIndexedDocuments(): int
    {
        return $this->indexedDocuments;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}