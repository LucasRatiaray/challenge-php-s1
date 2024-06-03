<?php

namespace App\Forms;

class ResetPassword
{
    public static function getConfig(): array
    {
        return [
            "config" => [
                "action" => "",
                "method" => "POST",
                "submit" => "Réinitialiser le mot de passe"
            ],
            "inputs" => [
                "password" => [
                    "type" => "password",
                    "placeholder" => "Nouveau mot de passe",
                    "required" => true,
                    "error" => "Votre mot de passe doit faire au minimum 8 caractères avec des lettres et des chiffres"
                ]
            ]
        ];
    }
}
