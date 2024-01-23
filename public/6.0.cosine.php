<?php

require __DIR__ . '/../vendor/autoload.php';

function embed(string $message): array
{
    $client = OpenAI::client(getenv('OPENAI_API_KEY'));

    return $client->embeddings()->create([
        'model' => 'text-embedding-ada-002',
        'input' => $message,
    ])->embeddings[0]->embedding;
}

$embed1 = embed('Christmas is coming');
$embed2 = embed('Santa Claus is coming to town');

print_r([
    \App\Cosine::similarity($embed1, $embed2),
]);
