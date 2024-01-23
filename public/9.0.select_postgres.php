<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Embedding;
use App\Repository\PostgresRepository;

$repository = new PostgresRepository();
$data = $repository->fetchSimilar(Embedding::create('testing'));

print_r($data);