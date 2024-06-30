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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function save(): bool
    {
        if ($this->id !== null) {
            // Update existing article
            $sql = "UPDATE chall_article SET title = :title, content = :content, description = :description, date_updated = NOW() WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            // Insert new article
            $sql = "INSERT INTO chall_article (title, content, description, user_id, date_inserted, date_updated) VALUES (:title, :content, :description, :user_id, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        }

        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getArticleById(int $id): ?self
    {
        $sql = "SELECT * FROM chall_article WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class) ?: null;
    }

    public function getAllArticles(): array
    {
        $sql = "SELECT * FROM chall_article";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM chall_article WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function getCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM chall_article");
        return (int) $stmt->fetchColumn();
    }


    public function getLatestArticle(): ?self
    {
        $stmt = $this->pdo->query("SELECT * FROM chall_article ORDER BY date_inserted DESC LIMIT 1");
        return $stmt->fetchObject(self::class) ?: null;
    }
}
