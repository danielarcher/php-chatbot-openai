<?php

namespace App\Repository;

use App\Db;
use App\Embedding;
use PDO;
use Probots\Pinecone\Client as Pinecone;

class PineconeRepository implements Repository
{
    private Pinecone $client;

    public function __construct()
    {
        $this->client = new Pinecone(getenv('PINECONE_API_KEY'), getenv('PINECONE_API_ENVIRONMENT'));
    }

    public function save(array $data): int
    {
        $db = Db::get();
        $statement = $db->prepare('INSERT INTO recipes (name, url, description, author, ingredients, method) VALUES (:name, :url, :description, :author, :ingredients, :method)');
        $statement->bindValue(':name', $data['name']);
        $statement->bindValue(':url', $data['url']);
        $statement->bindValue(':description', $data['description']);
        $statement->bindValue(':author', $data['author']);
        $statement->bindValue(':ingredients', implode("\n",$data['ingredients']));
        $statement->bindValue(':method', implode("\n",$data['method']));

        $statement->execute();
        return $db->lastInsertId();
    }

    public function store(string $text, array $vector, ?int $id = null): void
    {
        $this->client->index('questions')->vectors()->upsert(vectors: [
            'id' => ''.$id,
            'values' => $vector,
        ]);
    }

    public function fetchSimilar(array $vector, int $topk = 3): array
    {
        $response = $this->client->index('questions')->vectors()->query(
            vector: $vector,
            topK: $topk,
        );

        return array_map(function ($item) {
            $entry = Db::get()->query('SELECT name, url, description, author, ingredients, method FROM recipes WHERE id = '.$item['id'])->fetch(PDO::FETCH_ASSOC);
            if ($entry) {
                $itemAsText = 'Name: ' . $entry['name'] . '. Description: ' . $entry['description'] . '. Author: ' . $entry['author'] . '. Ingredients: ' . $entry['ingredients'] . '. Method: ' . $entry['method'];
            }
            return [
                'id' => $item['id'],
                'score' => $item['score'],
                'text' => $itemAsText ?? 'not found',
            ];
        }, $response->json()['matches'] ?? []);
    }

    public function cleanUp(): void
    {
        $db = Db::get();
        $statement = $db->query('SELECT id FROM recipes');
        $ids = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($ids as $id) {
            try {
                $this->client->index('questions')->vectors()->delete([
                    $id['id'],
                ]);
            } catch (\Throwable $th) {
                //ignore
            }
        }
        $db->exec('TRUNCATE recipes');
    }

    public function import(string $file): void
    {
        $this->cleanUp();
        $data = json_decode(file_get_contents($file), true);
        foreach ($data as $item) {
            $itemAsText = 'Name: '.$item['name'] . '. Description: ' . $item['description'] . '. Author: ' . $item['author'] . '. Ingredients: ' . implode("\n", $item['ingredients']) . '. Method: ' . implode("\n", $item['method']);
            $this->store($itemAsText, Embedding::create($itemAsText), $this->save($item));
        }
    }
}