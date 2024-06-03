<?php

namespace App\Controller;

use App\Core\Security as Auth;

class SecurityController
{
    public function login(): void
    {
        $security = new Auth();
        if ($security->isLogged()) {
            echo "Vous êtes déjà connecté";
        } else {
            echo "Se connecter";
        }
    }
    public function register(): void
    {
        echo "S'inscrire";
    }
    public function logout(): void
    {
        echo "Se déconnecter";
    }
}
