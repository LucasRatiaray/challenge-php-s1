<?php

namespace App\Models;

use App\Core\SQL;

class Media extends SQL
{
    private ?int $id=null;
    protected string $title;
    protected string $lien;
    protected string $description;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = ucwords(strtolower(($title)));
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getLien(): string
    {
        return $this->lien;
    }

    /**
     * @param string $lien
     */
    public function setlien(string $lien): void
    {
        $this->lien = $lien;
    }

    public function findOneByTitle(string $title) {
        $sql = "SELECT * FROM {$this->table} WHERE title = :title";

        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute([":title" => $title]);
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, 'App\Models\Media');
        return $queryPrepared->fetch();
    }

    public function findOneById(string $id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";

        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute([":id" => $id]);
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, 'App\Models\Media');
        return $queryPrepared->fetch();
    }

    public function findAll() {
        $sql = "SELECT * FROM {$this->table}";

        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute();
        $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, 'App\Models\Media');
        return $queryPrepared->fetchAll();
    }

    public function delete(): void
    {
        if (!empty($this->getId())) {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $queryPrepared = $this->pdo->prepare($sql);
            $queryPrepared->execute([':id' => $this->getId()]);
        }
    }

}