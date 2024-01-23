<?php

require __DIR__ . '/../vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$response = $client->chat()->create([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => '...'],
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'], //16k
        ['role' => 'user', 'content' => '...'], //remove older 2 messages
        ['role' => 'assistant', 'content' => '...'], //16k
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],//16k
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],//16k
        ['role' => 'user', 'content' => '...'],
        ['role' => 'assistant', 'content' => '...'],//16k
    ],
]);

print_r($response->choices[0]->message->content);
