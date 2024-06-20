<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Form;
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

    public function list(): void
    {
        $user = (new User())->getUserById($_SESSION['user_id']);
        $pages = new Page();
        $pages ->getAllPages();
        $view = new View("Page/home", "back");
        $view->assign('pages', $pages);
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
}
