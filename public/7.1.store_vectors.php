<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Embedding;
use App\Repository\PineconeRepository;

$text = 'How to make a lasagna?';
$vector = Embedding::create($text);

$pineconeRepo = new PineconeRepository();
$pineconeRepo->store($text, $vector, mt_rand());

print_r($pineconeRepo->fetchSimilar($vector,1));