<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Embedding;
use App\Repository\PineconeRepository;

$repository = new PineconeRepository();
$data = $repository->fetchSimilar(Embedding::create('ice cream'));

print_r($data);