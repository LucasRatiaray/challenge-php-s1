<?php
namespace App\Forms;

class CommentaireForm
{
    public static function getConfig(array $data = []): array
    {
        return [
            "config" => [
                "action" => "/store-comment?article_id=" . ($data['article_id'] ?? ''),
                "method" => "POST",
                "submit" => "Ajouter un commentaire"
            ],
            "inputs" => [
                "content" => [
                    "type" => "textarea",
                    "placeholder" => "Votre commentaire",
                    "label" => "Commentaire",
                    "required" => true,
                    "error" => "Entrer un commentaire",
                    "value" => $data['content'] ?? ''
                ]
            ]
        ];
    }
}
