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
            echo "No article ID specified.";
            return;
        }

        $articleId = $_GET['id'];
        $article = (new Article())->getArticleById($articleId);
        if ($article === null) {
            echo "Article not found.";
            return;
        }

        // Fetch comments for the article
        $comments = (new Commentaire())->getCommentsByArticleId($articleId);

        $view = new View("Article/viewArticle");
        $view->assign("article", $article);
        $view->assign("comments", $comments); // Pass comments to the view
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

}
