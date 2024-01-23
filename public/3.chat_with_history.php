<?php
require __DIR__ . '/../vendor/autoload.php';

$client = OpenAI::client(getenv('OPENAI_API_KEY'));

// Step 1: Identify the session
$session = $argv[1] ?? session_create_id();
session_id($session);
session_start();

$message = $argv[2];

// Step 2: Save a file with the session if doesn't exist
$fileName = "storage/session_$session.json";
if (!file_exists($fileName)) {
    file_put_contents($fileName, json_encode([]));
}

// Step 3: Take the history from the file, insert the new message
$history = json_decode(file_get_contents($fileName), true);
$history[] = ['role' => 'user', 'content' => $message];

// Step 4: Use the array as history for the chat creation
$response = $client->chat()->create([
    'model' => 'gpt-3.5-turbo-1106',
    'messages' => $history,
]);

// Step 5: Save the response as role 'assistant' in the file
$history[] = ['role' => 'assistant', 'content' => $response['choices'][0]['message']['content']];
file_put_contents($fileName, json_encode($history));

echo $response['choices'][0]['message']['content'].PHP_EOL;
