<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Embedding;
use App\Repository\PineconeRepository;

$repository = new PineconeRepository();
$repository->cleanUp();

$data = json_decode(file_get_contents(__DIR__ . '/../storage/recipes_short.json'), true);
foreach ($data as $item) {
    $id = $repository->save($item);
    $itemAsText = 'Name: '.$item['name'] . '. Description: ' . $item['description'] . '. Author: ' . $item['author'] . '. Ingredients: ' . implode("\n", $item['ingredients']) . '. Method: ' . implode("\n", $item['method']);
    $repository->store($itemAsText, Embedding::create($itemAsText), $id);
}