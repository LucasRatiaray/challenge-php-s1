<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Form;
use App\Models\User;
class UserController
{

    public function delete(): void
    {
        if (isset($_GET['id'])) {
            $userId = intval($_GET['id']);
            $user = (new User())->findOneById($userId);

            if ($user) {
                $user->delete();
                header('Location: /users/home');
                exit();
            } else {
                echo "Utilisateur non trouvé";
            }
        } else {
            echo "ID utilisateur non spécifié";
        }
    }
    public function show(): void
    {
        echo "Affichage d'un user";
    }

    public function add(): void
    {
        $user = (new User())->findOneById($_SESSION['user_id']);
        $userForm = new Form("User");

        if( $userForm->isSubmitted() && $userForm->isValid()) {
            $dbUser = (new User())->findOneByEmail($_POST["email"]);
            if ($dbUser) {
                echo "Ce nom de user est déjà pris";
                exit;
            }

            $validation_code = md5(uniqid(rand(), true));

            $user = new User();
            $user->setFirstname($_POST["firstname"]);
            $user->setLastname($_POST["lastname"]);
            $user->setEmail($_POST["email"]);
            $user->setPassword($_POST["password"]);
            $user->setValidationCode($validation_code);
            $user->setStatus(1);
            $user->save();
        }

        $view = new View("Users/create", "back");
        $view->assign('userForm', $userForm->build());
        $view->render();
    }
    public function list(): void
    {
        $currentUser = (new User())->findOneById($_SESSION['user_id']);

        $userModel = new User();
        $users = $userModel->findAll();

        $view = new View("Users/home", "back");
        $view->assign('users', $users);
        $view->render();
    }

    public function edit(): void
    {
        if (isset($_GET['id'])) {
            $userId = intval($_GET['id']);
            $user = (new User())->findOneById($userId);

            if ($user) {
                $userForm = new Form("UpdateUser");
                $userForm->setValues([
                    'firstname' => $user->getFirstname(),
                    'lastname' => $user->getLastname(),
                    'email' => $user->getEmail(),
                    'status' => $user->getStatus()
                ]);

                if ($userForm->isSubmitted() && $userForm->isValid()) {
                    $user->setFirstname($_POST["firstname"]);
                    $user->setLastname($_POST["lastname"]);
                    $user->setEmail($_POST["email"]);
                    $user->setStatus(intval($_POST["role"]));
                    $user->save();

                    header('Location: /users/home');
                    exit();
                }

                $view = new View("Users/edit", "back");
                $view->assign('userForm', $userForm->build());
                $view->render();
            } else {
                echo "User non trouvé !";
            }
        } else {
            echo "ID user non spécifié !";
        }
    }

}