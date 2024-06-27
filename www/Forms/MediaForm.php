<?php
namespace App\Forms;

class MediaForm
{
    public static function getConfig(array $data = []): array
    {
        return [
            "config" => [
                "action" => "",
                "method" => "POST",
                "enctype" => "multipart/form-data",
                "submit" => "Valider"
            ],
            "inputs" => [
                "title" => [
                    "type" => "text",
                    "label" => "Title",
                    "required" => true,
                    "min" => 2,
                    "max" => 50,
                    "value" => $data['title'] ?? ''
                ],
                "description" => [
                    "type" => "text",
                    "label" => "Description",
                    "required" => true,
                    "min" => 2,
                    "max" => 100,
                    "value" => $data['description'] ?? ''
                ],
                "image" => [
                    "type" => "file",
                    "label" => "Image",
                    "required" => true
                ]
            ]
        ];
    }
}