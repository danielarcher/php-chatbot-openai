<?php

namespace App\Repository;

use App\Db;
use App\Embedding;
use PDO;

class PostgresRepository implements Repository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::get();
    }

    public function store(string $text, array $vector, ?int $id = null): void
    {
        $statement = $this->db->prepare('INSERT INTO datasource (text, vector) VALUES (:text, :vector)');
        $statement->bindValue(':text', $text);
        $statement->bindValue(':vector', json_encode($vector));
        $statement->execute();
    }

    public function fetchSimilar(array $vector, int $topk = 3): array
    {
        $statement = $this->db->prepare('SELECT id, 1-cosine_distance(:vector, vector) as distance, text FROM datasource ORDER BY distance DESC LIMIT :topk');
        $statement->bindValue(':vector', json_encode($vector));
        $statement->bindValue(':topk', $topk, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cleanUp(): void
    {
        $this->db->exec('TRUNCATE TABLE datasource');
    }

    public function import(string $file)
    {
        $data = json_decode(file_get_contents($file), true);
        $this->cleanUp();
        foreach ($data as $item) {
            $itemAsText = 'Name: ' . $item['name'] . '. Description: ' . $item['description'] . '. Author: ' . $item['author'] . '. Ingredients: ' . implode("\n", $item['ingredients']) . '. Method: ' . implode("\n", $item['method']);
            $this->store($itemAsText, Embedding::create($itemAsText));
        }
    }
}