<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Form;
use App\Core\Security as Auth;
use App\Models\User;
use App\Forms\EditUser;

class UserController
{
    private function checkSession(): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }

    public function delete(): void
    {
        if (!$this->checkSession()) {
            echo "User not logged in.";
            return;
        }

        $security = new Auth();
        if (!$security->hasRole(['admin'])) {
            echo "Access denied.";
            return;
        }

        if (isset($_POST['id'])) {
            $userId = intval($_POST['id']);
            $user = new User();
            $user->setId($userId);

            if ($user->delete()) {
                header("Location: /list-users");
                exit();
            } else {
                echo "There was an error deleting the user.";
            }
        } else {
            echo "User ID not provided.";
        }
    }

    public function profil(): void
    {
        if (!$this->checkSession()) {
            echo "User not logged in.";
            return;
        }

        $userId = $_SESSION['user_id'];
        $user = new User();
        $userInfo = $user->getUserById($userId);

        $view = new View("User/userProfil");
        $view->assign("user", $userInfo);
        $view->render();
    }


    public function add(): void
    {
        if (!$this->checkSession()) {
            echo "User not logged in.";
            return;
        }

        $security = new Auth();
        if (!$security->hasRole(['admin'])) {
            echo "Access denied.";
            return;
        }

        $form = new Form("UserForm");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($form->isSubmitted() && $form->isValid()) {
                $user = new User();
                $user->setFirstname($_POST['firstname']);
                $user->setLastname($_POST['lastname']);
                $user->setEmail($_POST['email']);
                $user->setPassword($_POST['password']);

                if ($user->save()) {
                    header("Location: /list-users");
                    exit();
                } else {
                    echo "There was an error adding the user.";
                }
            } else {
                $formHtml = $form->build();
                $view = new View("User/addUser");
                $view->assign("userForm", $formHtml);
                $view->assign("errors", $form->getErrors());
                $view->render();
            }
        } else {
            $formHtml = $form->build();
            $view = new View("User/addUser");
            $view->assign("userForm", $formHtml);
            $view->render();
        }
    }
    public function list(): void
    {
        if (!$this->checkSession()) {
            echo "User not logged in.";
            return;
        }

        $userId = $_SESSION['user_id'];
        $user = new User();
        $security = new Auth();

        if (!$security->hasRole(['admin'])) {
            echo "Access denied.";
            return;
        }

        $users = $user->getAllUsers();
        $view = new View("User/listUsers");
        $view->assign("users", $users);
        $view->render();
    }

    public function edit(): void
    {
        if (!$this->checkSession()) {
            echo "User not logged in.";
            return;
        }

        if (isset($_GET['id'])) {
            $userId = intval($_GET['id']);
            $user = new User();
            $userInfo = $user->getUserById($userId);

            $formConfig = EditUser::getConfig($userId);
            $form = new Form($formConfig);
            $formHtml = $form->build();

            $view = new View("User/editUser");
            $view->assign("form", $formHtml);
            $view->assign("user", $userInfo);
            $view->render();
        } else {
            echo "User ID not provided.";
        }
    }

    public function update(): void
    {
        if (!$this->checkSession()) {
            echo "User not logged in.";
            return;
        }

        $security = new Auth();
        if (!$security->hasRole(['admin'])) {
            echo "Access denied.";
            return;
        }

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $userId = intval($_POST['id']); // Assurez-vous que l'ID est un entier
            $user = new User();
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
            echo "User ID not provided.";
        }
    }

    public function editUser(): void
    {
        if (!$this->checkSession()) {
            echo "User not logged in.";
            return;
        }

        if (isset($_GET['id'])) {
            $userId = intval($_GET['id']);
            $user = new User();
            $userInfo = $user->getUserById($userId);

            $formConfig = EditUser::getConfig($userId);
            $form = new Form($formConfig);
            $formHtml = $form->build();

            $view = new View("User/editUser");
            $view->assign("form", $formHtml);
            $view->assign("user", $userInfo);
            $view->render();
        } else {
            echo "User ID not provided.";
        }
    }


    public function updateUsersInline(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $security = new Auth();
            if ($security->hasRole(['admin'])) {
                if (isset($_POST['users'])) {
                    $usersData = $_POST['users'];
                    $user = new User();

                    foreach ($usersData as $userId => $userData) {
                        $user->setId(intval($userId));
                        $user->setFirstname($userData['firstname']);
                        $user->setLastname($userData['lastname']);
                        $user->setEmail($userData['email']);
                        $user->update();
                    }
                    header("Location: /list-users");
                    exit();
                } else {
                    echo "No user data provided.";
                }
            } else {
                echo "Access denied.";
            }
        } else {
            echo "User not logged in.";
        }
    }

}
