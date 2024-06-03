<?php
namespace App\Controller;

use App\Core\Form;
use App\Core\Security as Auth;
use App\Core\SQL;
use App\Core\View;
use App\Models\User;

class Security {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(): void {
        $user = new User();
        $security = new Auth();

        if ($security->isLogged()) {
            echo "Vous êtes déjà connecté";
            return;
        }

        $form = new Form("Login");

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Debugging statement to check login data
            error_log("Login attempt with email: $email");

            if ($user->login($email, $password)) {
                // Debugging statement to confirm successful login
                error_log("Login successful for email: $email");
                header("Location: /dashboard");
                exit();
            } else {
                echo "Informations d'identification incorrectes";
                // Debugging statement to confirm failed login
                error_log("Login failed for email: $email");
            }
        }

        $view = new View("Security/login");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function register(): void {
        $form = new Form("Register");

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setFirstname($_POST["firstname"]);
            $user->setLastname($_POST["lastname"]);
            $user->setEmail($_POST["email"]);
            $user->setPassword(password_hash($_POST["password"], PASSWORD_BCRYPT));
            $user->save();
            header("Location: /login");
            exit();
        }

        $view = new View("Security/register");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function logout(): void {
        unset($_SESSION['user_id']);
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function dashboard(): void {
        $security = new Auth();
        if (!$security->isLogged()) {
            header("Location: /login");
            exit();
        }

        $view = new View("Security/dashboard");
        $view->render();
    }
}
