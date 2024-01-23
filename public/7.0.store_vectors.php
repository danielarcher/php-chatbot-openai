<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Embedding;
use App\Repository\PostgresRepository;

$text = 'How to make a lasagna?';
$vector = Embedding::create($text);

$postgresRepo = new PostgresRepository();
$postgresRepo->store($text, $vector);

print_r($postgresRepo->fetchSimilar($vector,1));