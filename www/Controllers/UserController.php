<?php

namespace App\Controller;

use App\Core\Form;
use App\Core\Security;
use App\Core\Security as Auth;
use App\Core\View;
use App\Forms\EditUser;
use App\Models\User;

class UserController
{

    private $security;


    public function __construct()
    {
        $this->security = new Security();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $security = new Auth();

        if (!$security->isLogged()) {
            echo "Vous devez vous  connectézz";
            return;
        }
    }

    public function delete(): void
    {
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


        $userId = $_SESSION['user_id'];
        $user = new User();
        $userInfo = $user->getUserById($userId);

        $view = new View("User/userProfil");
        $view->assign("user", $userInfo);
        $view->render();
    }


    public function add(): void
    {
        $security = new Auth();
        if (!$security->hasRole(['admin'])) {
            echo "Access denied.";
            return;
        }

        $form = new Form("UserForm");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($form->isSubmitted() && $form->isValid()) {
                $user = new User();
                $email = $_POST['email'];

                // Vérifier si l'email existe déjà
                if ($user->exists($email)) {
                    echo "L'adresse email est déjà utilisée.";
                } else {
                    $user->setFirstname($_POST['firstname']);
                    $user->setLastname($_POST['lastname']);
                    $user->setEmail($email);
                    $user->setPassword($_POST['password']);
                    $user->setRole($_POST['role']);

                    if ($user->save()) {
                        header("Location: /list-users");
                        exit();
                    } else {
                        echo "There was an error adding the user.";
                    }
                }
            } else {
                $formHtml = $form->build();
                $view = new View("User/addUser");
                $view->assign("userForm", $formHtml);
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

        $userId = $_SESSION['user_id'];
        $user = new User();
        $security = new Auth();

        if (!$security->hasRole(['admin'])) {
            echo "Access denied.";
            return;
        }

        $users = $user->getAllUsers();
        $view = new View("User/listUsers", "back");
        $view->assign("users", $users);
        $view->render();
    }

    public function edit(): void
    {

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


        $security = new Auth();
        if (!$security->hasRole(['admin'])) {
            echo "Access denied.";
            return;
        }

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $userId = intval($_POST['id']);
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
                    $currentUserId = $_SESSION['user_id'];

                    foreach ($usersData as $userId => $userData) {
                        $user->setId(intval($userId));
                        $user->setFirstname($userData['firstname']);
                        $user->setLastname($userData['lastname']);
                        $user->setEmail($userData['email']);

                        if (isset($userData['role']) && $userId != $currentUserId) {
                            $user->setRole($userData['role']);
                        } elseif ($userId == $currentUserId) {
                            $userInfo = $user->getUserById($userId);
                            $user->setRole($userInfo['role']);
                        }

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