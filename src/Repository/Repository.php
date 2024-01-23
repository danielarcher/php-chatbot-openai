<?php

namespace App\Repository;

interface Repository
{
    public function store(string $text, array $vector, ?int $id = null): void;
    public function fetchSimilar(array $vector, int $topk = 3): array;
    public function cleanUp(): void;
}