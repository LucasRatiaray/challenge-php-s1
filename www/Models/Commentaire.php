<?php

namespace App\Models;

use App\Core\SQL;

class Commentaire extends SQL
{
    private ?int $id = null;
    protected int $user_id;
    protected int $article_id;
    protected string $content;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getArticleId(): int
    {
        return $this->article_id;
    }

    public function setArticleId(int $article_id): void
    {
        $this->article_id = $article_id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function save(): bool
    {
        if ($this->id) {
            $sql = "UPDATE {$this->table} SET content = :content, user_id = :user_id, article_id = :article_id WHERE id = :id";
            $query = $this->pdo->prepare($sql);
            $result = $query->execute([
                ':content' => $this->getContent(),
                ':user_id' => $this->getUserId(),
                ':article_id' => $this->getArticleId(),
                ':id' => $this->getId(),
            ]);
        } else {
            $sql = "INSERT INTO {$this->table} (content, user_id, article_id) VALUES (:content, :user_id, :article_id)";
            $query = $this->pdo->prepare($sql);
            $result = $query->execute([
                ':content' => $this->getContent(),
                ':user_id' => $this->getUserId(),
                ':article_id' => $this->getArticleId(),
            ]);
            if ($result) {
                $this->id = $this->pdo->lastInsertId();
            }
        }

        return $result;
    }
}