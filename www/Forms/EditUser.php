<?php

namespace App\Forms;

class EditUser
{
    public static function getConfig(): array
    {
        return [
            "config" => [
                "action" => "/update",
                "method" => "POST",
                "submit" => "Valider"
            ],
            "inputs" => [
                "firstname" => [
                    "type" => "text",
                    "min" => 2,
                    "max" => 50,
                    "placeholder" => "prénom",
                    "required" => true,
                    "error" => "Votre prénom doit faire entre 2 et 50 caractères",
                ],
                "lastname" => [
                    "type" => "text",
                    "min" => 2,
                    "max" => 50,
                    "placeholder" => "nom",
                    "required" => true,
                    "error" => "Votre nom doit faire entre 2 et 50 caractères",
                ],
                "email" => [
                    "type" => "email",
                    "min" => 8,
                    "max" => 320,
                    "placeholder" => "mail",
                    "error" => "Votre email doit faire entre 8 et 320 caractères",
                ]
            ]
        ];
    }
}
