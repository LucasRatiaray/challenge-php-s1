<?php

namespace App\Core;

use App\Models\User;
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

    public function hasRole(array $roles): bool
    {
        if ($this->isLogged()) {
            $userId = $_SESSION['user_id'];
            $user = new User();
            $userDetails = $user->getUserById($userId);
            return in_array($userDetails['role'], $roles);
        }
        return false;
    }
}
