<?php

namespace App;

use OpenAI;
use PDO;

class Embedding
{
    private mixed $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }
    public static function create(string $text): array
    {
        $client = OpenAI::client(getenv('OPENAI_API_KEY'));

        $response = $client->embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $text,
        ]);

        return $response->embeddings[0]->embedding;
    }

    public static function storeIntoDb(string $text, array $vector): void
    {
        $db = Db::get();
        $statement = $db->prepare('INSERT INTO datasource (text, vector) VALUES (:text, :vector)');
        $statement->bindValue(':text', $text);
        $statement->bindValue(':vector', json_encode($vector));
        $statement->execute();
    }

    public static function fetchSimilar(array $vector, int $topk = 3): array
    {
        $db = Db::get();
        $statement = $db->prepare('SELECT id, 1-cosine_distance(:vector, vector) as distance, text FROM datasource ORDER BY distance DESC LIMIT :topk');
        $statement->bindValue(':vector', json_encode($vector));
        $statement->bindValue(':topk', $topk, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}