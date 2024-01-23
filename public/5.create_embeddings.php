<?php

require __DIR__ . '/../vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$response = $client->embeddings()->create([
    'model' => 'text-embedding-ada-002',
    'input' => 'PHP',
]);


print_r($response->embeddings[0]->embedding);
