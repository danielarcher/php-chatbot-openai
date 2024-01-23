<?php

require __DIR__ .
    '/../vendor/autoload.php';

use App\Embedding;
use App\Repository\PostgresRepository;

$data = json_decode(file_get_contents(__DIR__ .
    '/../storage/recipes_short.json'), true);

$repository = new PostgresRepository();
$repository->cleanUp();
foreach ($data as $item) {
    $itemAsText = 'Name: ' .
        $item['name'] .
        '. Description: ' . $item['description'] .
        '. Author: ' . $item['author'] .
        '. Ingredients: ' . implode("\n", $item['ingredients']) .
        '. Method: ' . implode("\n", $item['method']);
    $generatedByIa = //chat gpt call, summarizing and adding possible recipes to my recipe
    $repository->store($itemAsText, Embedding::create($itemAsText));
}