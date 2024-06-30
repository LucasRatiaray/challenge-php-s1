<?php
namespace App\Models;

use App\Core\SQL;
use PDO;
use PDOException;

class Commentaire extends SQL
{
    private ?int $id = null;
    protected int $article_id;
    protected int $user_id;
    protected string $content;
    protected bool $reported = false;
    protected string $date_inserted;
    protected string $date_updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleId(): int
    {
        return $this->article_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isReported(): bool
    {
        return $this->reported;
    }

    public function getDateInserted(): string
    {
        return $this->date_inserted;
    }

    public function getDateUpdated(): string
    {
        return $this->date_updated;
    }

    public function setArticleId(int $article_id): void
    {
        $this->article_id = $article_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setReported(bool $reported): void
    {
        $this->reported = $reported;
    }

    public function save(): bool
    {
        $sql = "INSERT INTO chall_commentaire (article_id, user_id, content, reported, date_inserted, date_updated)
                VALUES (:article_id, :user_id, :content, :reported, NOW(), NOW())
                ON CONFLICT (id) DO UPDATE SET content = :content, reported = :reported, date_updated = NOW()";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':article_id', $this->article_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);
        $stmt->bindParam(':reported', $this->reported, PDO::PARAM_BOOL);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving comment: " . $e->getMessage());
            error_log("SQLSTATE: " . $e->getCode());
            error_log("SQL Query: " . $sql);
            return false;
        }
    }

    public function report(): bool
    {
        $sql = "UPDATE chall_commentaire SET reported = TRUE, date_updated = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error reporting comment: " . $e->getMessage());
            return false;
        }
    }

    public function getCommentsByArticleId(int $articleId): array
    {
        $sql = "SELECT * FROM chall_commentaire WHERE article_id = :article_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\Models\Commentaire');
    }

    public function findOneById(int $id): ?self
    {
        $sql = "SELECT * FROM chall_commentaire WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class) ?: null;
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM chall_commentaire WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting comment: " . $e->getMessage());
            return false;
        }
    }


    public function getCount(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM chall_commentaire");
        return (int) $stmt->fetchColumn();
    }


    public function getLatestComment(): ?self
    {
        $stmt = $this->pdo->query("SELECT * FROM chall_commentaire ORDER BY date_inserted DESC LIMIT 1");
        return $stmt->fetchObject(self::class) ?: null;
    }
}
