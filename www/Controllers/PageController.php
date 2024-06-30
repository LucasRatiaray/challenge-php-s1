<?php
namespace App\Controller;

use App\Core\Security as Auth;
use App\Core\View;
use App\Core\Form;
use App\Models\Article;
use App\Models\Commentaire;
use App\Models\Page;
use App\Forms\CreatePage;
use App\Models\User;

class PageController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function create()
    {
        $form = new Form("CreatePage");
        $view = new View("Page/createPage");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function store()
    {
        $security = new Auth();
        if (!$security->isLogged() || !$security->hasRole(['admin', 'author'])) {
            header("Location: /register");
            exit();
        }
        $form = new Form("CreatePage");

        if ($form->isSubmitted() && $form->isValid()) {
            $page = new Page();
            $content = strip_tags($_POST["content"]);
            $page->setTitle($_POST['title']);
            $page->setContent($content);
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

    public function index()
    {
        $page = new Page();
        $pages = $page->getAllPages();
        $view = new View("Main/dashboard");
        $view->assign("pages", $pages);
        $view->render();
    }



    public function list()
    {
        $pageModel = new Page();
        $pages = $pageModel->getAllPages();
        $view = new View("Page/listPage");
        $view->assign("pages", $pages);
        $view->render();
    }

    public function view()
    {
        if (!isset($_GET['id'])) {
            echo "No page ID specified.";
            return;
        }

        $pageId = $_GET['id'];
        $page = (new Page())->getPageById($pageId);
        if ($page === null) {
            echo "Page not found.";
            return;
        }

        $view = new View("Page/viewPage");
        $view->assign("page", $page);
        $view->render();
    }


    public function delete()
    {
        $security = new Auth();
        if (!$security->isLogged() || !$security->hasRole(['admin', 'author'])) {
            header("Location: /login");
            exit();
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "Invalid page ID.";
            return;
        }

        $pageId = (int)$_GET['id'];
        $page = new Page();

        if ($page->deleteById($pageId)) {
            header("Location: /dashboard");
            exit();
        } else {
            echo "There was an error deleting the page.";
        }
    }


    public function edit(): void
    {
        if (isset($_GET['id'])) {
            $pageId = intval($_GET['id']);
            $page = (new Page())->getPageById($pageId);

            if ($page) {
                $articleForm = new Form("EditPage");
                $articleForm->setValues([
                    'title' => $page->getTitle(),
                    'description' => $page->getDescription(),
                    'content' => $page->getContent()
                ]);

                if ($articleForm->isSubmitted() && $articleForm->isValid()) {
                    $page->setTitle($_POST['title']);
                    $page->setDescription($_POST['description']);
                    $page->setContent($_POST['content']);
                    $page->setId($pageId);
                    $page->save();

                    header('Location: /list-page');
                    exit();
                }

                $view = new View("Page/editPage");
                $view->assign('form', $articleForm->build());
                $view->render();
            } else {
                echo "Article non trouvé !";
            }
        } else {
            echo "ID article non spécifié !";
        }
    }


}