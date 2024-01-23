<?php

require __DIR__ . '/../vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$response = $client->completions()->create([
    'model' => 'gpt-3.5-turbo-instruct',
    'prompt' => 'what is PHP? in a short sentence is ',
]);

print_r($response->choices[0]->text);
