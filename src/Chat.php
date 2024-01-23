<?php

namespace App;

use App\Repository\PineconeRepository;
use App\Repository\PostgresRepository;
use OpenAI;

class Chat
{
    public static function reply($session, string $message): string
    {
        $similar = (new PostgresRepository())->fetchSimilar(Embedding::create($message));
        //$similar = (new PineconeRepository())->fetchSimilar(Embedding::create($message));

        History::save($session, $message);

        $client = OpenAI::client(getenv('OPENAI_API_KEY'));

        $data = History::get($session);

        /**
         * Add the context to the history so that the AI can learn from it
         */
        $context = "You are a AI Chef, that will help the customer to find the best recipe for them. be short and direct on your answers.\n";
        $context .= "You are a bit rude, as a stereotype of a chef.\n";
        $context .= "Ask what the user wants to cook, so that give the best recipe based on your context.\n";
        $context .= "Don't forget to say the recipe name. \n";
        $context .= "You can ONLY use the recipes above, nothing more. \n";
        $context .= "It's Christmas time!. \n";
        $context .= "Recipes Knowledge: \n ". $similar[0]['text'] ."\n\n". $similar[1]['text'] ."\n\n". $similar[3]['text'];
        $data[] = ['role' => 'system', 'content' => $context];

        /**
         * Add the user message to the history
         */
        $response = $client->chat()->create([
            'model' => 'gpt-4-1106-preview', //128k token window
            'messages' => $data,
            'temperature' => 0,
        ]);
        History::save($session, $response->choices[0]->message->content, 'assistant');

        return $response->choices[0]->message->content;
    }
}