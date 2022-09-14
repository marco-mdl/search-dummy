<?php

namespace App\Entity;

class Indexing
{
    private string $index = 'test2';
    private array $files = [];

    public function getIndex(): string
    {
        return $this->index;
    }

    public function setIndex(string $index): Indexing
    {
        $this->index = $index;
        return $this;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): Indexing
    {
        $this->files = $files;
        return $this;
    }
}
