<?php

namespace App\Core;

use PDO;

class Security
{
    public function isLogged(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function logout(): void
    {
        unset($_SESSION['user_id']);
    }
}
