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

$embed1 = embed('PHP Conference');
$embed2 = embed('Developer');
$embed3 = embed('Las Vegas');
$embed4 = embed('Casinos');

print_r([
    'Conf -> Dev' => \App\Cosine::similarity($embed1, $embed2),
    'Conf -> Vegas' => \App\Cosine::similarity($embed1, $embed3),
    'Vegas -> casinos' => \App\Cosine::similarity($embed3, $embed4),
]);
