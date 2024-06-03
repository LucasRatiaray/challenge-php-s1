<?php

namespace App\Controller;

use App\Core\View;

class MainController
{
    public function home()
    {
        $view = new View("Main/home");
        $view->render();
    }

    public function dashboard()
    {
        $view = new View("Main/dashboard");
        $view->render();
    }
}
