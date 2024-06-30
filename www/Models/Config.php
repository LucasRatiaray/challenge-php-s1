<?php

namespace App\Models;

use App\Core\SQL;
use PDO;

class Config extends SQL
{
    private ?int $id = null;
    protected string $background_color = '#ffffff';
    protected string $font_color = '#000000';
    protected string $font_size = '16px';
    protected string $font_style = 'normal';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBackgroundColor(): string
    {
        return $this->background_color;
    }

    public function getFontColor(): string
    {
        return $this->font_color;
    }

    public function getFontSize(): string
    {
        return $this->font_size;
    }

    public function getFontStyle(): string
    {
        return $this->font_style;
    }

    public function setBackgroundColor(string $color): void
    {
        $this->background_color = $color;
    }

    public function setFontColor(string $color): void
    {
        $this->font_color = $color;
    }

    public function setFontSize(string $size): void
    {
        $this->font_size = $size;
    }

    public function setFontStyle(string $style): void
    {
        $this->font_style = $style;
    }

    public function getConfig()
    {
        $sql = "SELECT * FROM chall_config WHERE id = 1 LIMIT 1";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchObject(self::class);
    }

    public function updateConfig(): bool
    {
        $sql = "UPDATE chall_config SET background_color = :background_color, font_color = :font_color, font_size = :font_size, font_style = :font_style WHERE id = 1";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':background_color', $this->background_color, PDO::PARAM_STR);
        $stmt->bindParam(':font_color', $this->font_color, PDO::PARAM_STR);
        $stmt->bindParam(':font_size', $this->font_size, PDO::PARAM_STR);
        $stmt->bindParam(':font_style', $this->font_style, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
