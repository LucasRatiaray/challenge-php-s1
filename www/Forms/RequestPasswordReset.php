<?php

namespace App\Forms;

class RequestPasswordReset
{
    public static function getConfig(): array
    {
        return [
            "config" => [
                "action" => "",
                "method" => "POST",
                "submit" => "Demander la réinitialisation du mot de passe"
            ],
            "inputs" => [
                "email" => [
                    "type" => "email",
                    "min" => 8,
                    "max" => 320,
                    "placeholder" => "Votre email",
                    "required" => true,
                    "error" => "Votre email doit faire entre 8 et 320 caractères"
                ]
            ]
        ];
    }
}
