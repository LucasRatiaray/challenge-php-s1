<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Form;
use App\Models\Page;
use App\Forms\CreatePage;

class PageController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }


    public function index()
    {
        echo "Liste des pages";
    }

    public function create()
    {
        $form = new Form("CreatePage");
        $view = new View("Page/createPage");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function show()
    {
        echo "Afficher page";
    }

    public function edit()
    {
        echo "Modifier page";
    }

    public function delete()
    {
        echo "Supprimer page";
    }

    public function add()
    {
        echo "Ajouter page";
    }

    public function update()
    {
        echo "Mettre à jour page";
    }

    public function store()
    {
        $form = new Form("CreatePage");

        if ($form->isSubmitted() && $form->isValid()) {
            $page = new Page();
            $page->setTitle($_POST['title']);
            $page->setContent($_POST['content']);
            $page->setDescription($_POST['description'] ?? null);
            $page->setUserId($_SESSION['user_id']);

            if ($page->save()) {
                header("Location: /dashboard");
                exit();
            } else {
                echo "There was an error saving the page.";
            }
        } else {
            echo "There was an error creating the page.";
            foreach ($form->getErrors() as $error) {
                echo "<p>$error</p>";
            }
        }
    }
}
