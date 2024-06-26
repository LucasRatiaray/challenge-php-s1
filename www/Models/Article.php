<?php

namespace App\Models;

use App\Core\SQL;
use PDO;

class Article extends SQL
{
    private ?int $id = null;
    protected string $title;
    protected string $content;
    protected ?string $description = null;
    protected int $user_id;

    // Getters et setters pour les propriétés...

    public function save(): bool
    {
        $sql = "INSERT INTO articles (title, content, description, user_id) VALUES (:title, :content, :description, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update(): bool
    {
        $sql = "UPDATE articles SET title = :title, content = :content, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAllArticles(): array
    {
        $sql = "SELECT * FROM articles";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\Models\Article');
    }

    public function getArticleById(int $id): ?Article
    {
        $sql = "SELECT * FROM articles WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'App\Models\Article');
        return $stmt->fetch() ?: null;
    }
}
