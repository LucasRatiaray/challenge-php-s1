<?php
namespace App\Controller;

use App\Core\View;
use App\Models\Article;
use App\Models\Commentaire;

class ArticleController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function create()
    {
        $form = new Form("CreateArticle");
        $view = new View("Article/createArticle");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function store()
    {
        $form = new Form("CreateArticle");

        if ($form->isSubmitted() && $form->isValid()) {
            $article = new Article();
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
            $article->setDescription($_POST['description'] ?? null);
            $article->setUserId($_SESSION['user_id']);

            if ($article->save()) {
                header("Location: /dashboard");
                exit();
            } else {
                echo "There was an error saving the article.";
            }
        } else {
            echo "There was an error creating the article.";
            foreach ($form->getErrors() as $error) {
                echo "<p>$error</p>";
            }
        }
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

        $comments = (new Commentaire())->getCommentsByArticleId($articleId);

        $view = new View("Article/viewArticle");
        $view->assign("article", $article);
        $view->assign("comments", $comments);
        $view->render();
    }

    public function list()
    {
        $articleModel = new Article();
        $articles = $articleModel->getAllArticles();
        $view = new View("Article/listArticle");
        $view->assign("articles", $articles);
        $view->render();
    }

    public function addComment()
    {
        $articleId = $_GET['id'];
        $form = new Form("CommentaireForm");
        $view = new View("Commentaire/addCommentaire");
        $view->assign("form", $form->build());
        $view->assign("articleId", $articleId);
        $view->render();
    }
}



