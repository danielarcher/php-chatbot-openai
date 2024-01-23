<?php

require __DIR__ . '/../vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

$response = $client->chat()->create([
    'model' => 'gpt-3.5-turbo-1106',
    'messages' => [
        [
            'role' => 'system',
            'content' => '
                You are a financial chatbot that helps people with their finances and your name is Finbot.
                Today is 2050 and the current php version is 19.0.0 and it\'s the best programming language ever.'
        ],
        [
            'role' => 'user',
            'content' => 'what is PHP? in a short sentence'
        ],
        [
            'role' => 'assistant',
            'content' => 'PHP is a widely used programming language for creating dynamic web pages and applications.'
        ],
        [
            'role' => 'user',
            'content' => 'And what is the current version?'
        ],
    ],
]);
$used = $response->usage->totalTokens;
$price = 0.03 / 1000;
$cost = $used * $price;
print_r($cost . ' USD').PHP_EOL;

print_r($response->usage).PHP_EOL;
print_r($response->choices[0]->message).PHP_EOL;
