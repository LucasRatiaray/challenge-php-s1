<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Form;
use App\Models\User;
use App\Forms\EditUser;
use App\Core\Security as Auth;

class UserController
{
    public function delete(): void
    {
        // Méthode de suppression
    }

    public function profil(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $user = new User();
            $userInfo = $user->getUserById($userId);

            $view = new View("User/userProfil");
            $view->assign("user", $userInfo);
            $view->render();
        } else {
            echo "User not logged in.";
        }
    }

    public function add(): void
    {
        // Méthode d'ajout
    }

    public function list(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $user = new User();
            $security = new Auth();

            if ($security->hasRole(['admin'])) {
                $users = $user->getAllUsers();

                $view = new View("User/listUsers");
                $view->assign("users", $users);
                $view->render();
            } else {
                echo "Access denied.";
            }
        } else {
            echo "User not logged in.";
        }
    }

    public function edit(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $user = new User();
            $userInfo = $user->getUserById($userId);

            $form = new Form("EditUser");
            $formHtml = $form->build();

            $view = new View("User/editUser");
            $view->assign("form", $formHtml);
            $view->assign("user", $userInfo);
            $view->render();
        } else {
            echo "User not logged in.";
        }
    }

    public function update(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $user = new User();

            $form = new Form("EditUser");

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setId($userId);
                $user->setFirstname($_POST['firstname']);
                $user->setLastname($_POST['lastname']);
                $user->setEmail($_POST['email']);

                if ($user->update()) {
                    header("Location: /profil-user");
                    exit();
                } else {
                    echo "There was an error updating the profile.";
                }
            } else {
                $userInfo = $user->getUserById($userId);
                $formHtml = $form->build();

                $view = new View("User/editUser");
                $view->assign("form", $formHtml);
                $view->assign("user", $userInfo);
                $view->render();
            }
        } else {
            echo "User not logged in.";
        }
    }
}