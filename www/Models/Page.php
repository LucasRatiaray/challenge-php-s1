<?php

namespace App\Models;

use App\Core\SQL;
use PDO;

class Page extends SQL
{
    private ?int $id = null;
    protected string $title;
    protected string $content;
    protected ?string $description = null;
    protected int $user_id;
    protected string $slug;

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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $this->generateUniqueSlug($slug);
    }

    public function save(): bool
    {
        // Sanitize content before saving to prevent XSS
        $this->content = htmlspecialchars($this->content, ENT_QUOTES, 'UTF-8');

        if ($this->id !== null) {
            // Update existing page
            $sql = "UPDATE chall_page
                    SET title = :title, content = :content, description = :description, slug = :slug, date_updated = NOW()
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            // Insert new page
            $sql = "INSERT INTO chall_page (title, content, description, user_id, slug, date_inserted, date_updated)
                    VALUES (:title, :content, :description, :user_id, :slug, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        }

        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':slug', $this->slug, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function getAllPages(): array
    {
        $sql = "SELECT * FROM chall_page";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\Models\Page');
    }

    public function getPageById(int $id): ?self
    {
        $sql = "SELECT * FROM chall_page WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'App\Models\Page');
        return $stmt->fetch() ?: null;
    }

    public function getPageBySlug(string $slug): ?self
    {
        $sql = "SELECT * FROM chall_page WHERE slug = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'App\Models\Page');
        return $stmt->fetch() ?: null;
    }

    public function deleteById(int $id): bool
    {
        $sql = "DELETE FROM chall_page WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM chall_page");
        return (int) $stmt->fetchColumn();
    }

    public function getLatestPage(): ?self
    {
        $stmt = $this->pdo->query("SELECT * FROM chall_page ORDER BY date_inserted DESC LIMIT 1");
        return $stmt->fetchObject(self::class) ?: null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'slug' => $this->slug,
        ];
    }

    private function generateUniqueSlug($slug, $counter = 0)
    {
        $originalSlug = $slug;
        while ($this->isSlugExists($slug)) {
            $slug = $originalSlug . '-' . ++$counter;
        }
        return $slug;
    }

    private function isSlugExists($slug)
    {
        $sql = "SELECT COUNT(*) FROM chall_page WHERE slug = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn() > 0;
    }
}
