<?php

namespace App\Forms;

class EditPage
{
    public static function getConfig(): array
    {
        return [
            "config" => [
                "action" => "",
                "method" => "POST",
                "submit" => "Edit Page"
            ],
            "inputs" => [
                "title" => [
                    "type" => "text",
                    "min" => 2,
                    "max" => 255,
                    "placeholder" => "Title",
                    "required" => true,
                    "error" => "Title must be between 2 and 255 characters"
                ],
                "content" => [
                    "type" => "textarea",
                    "min" => 10,
                    "placeholder" => "Content",
                    "required" => true,
                    "error" => "Content must be at least 10 characters"
                ],
                "slug" => [
                    "type" => "text",
                    "max" => 60,
                    "placeholder" => "Slug",
                    "label" => "",
                ],
                "description" => [
                    "type" => "text",
                    "min" => 2,
                    "max" => 255,
                    "placeholder" => "Description",
                    "required" => false,
                    "error" => "Description must be between 2 and 255 characters"
                ]
            ]
        ];
    }
}
