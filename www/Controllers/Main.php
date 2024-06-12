<?php
namespace App\Controller;
use App\Core\Security as Auth;
use App\Core\View;
use App\Models\User;

class Main
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function home()
    {
        //Appeler un template Front et la vue Main/Home
        $view = new View("Main/home", "Back");
        //$view->setView("Main/Home");
        //$view->setTemplate("Front");
        $view->render();
    }
    public function logout()
    {
        //Déconnexion
        //Redirection
    }

    public function dashboard(): void
    {
        $security = new Auth();
        if (!$security->isLogged() ) {
            error_log("User is not logged in");
            header("Location: /register");
            exit();
        }

        $view = new View("Main/dashboard");
        $view->render();
    }

}