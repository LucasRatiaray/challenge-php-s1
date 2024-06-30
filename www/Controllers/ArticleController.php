<?php

namespace App\Controller;

use App\Core\Form;
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
                header("Location: /list-article");
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
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "Invalid article ID specified.";
            return;
        }

        $articleId = (int)$_GET['id'];
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

    public function edit(): void
    {
        if (isset($_GET['id'])) {
            $articleId = intval($_GET['id']);
            $article = (new Article())->getArticleById($articleId);

            if ($article) {
                $articleForm = new Form("EditArticle");
                $articleForm->setValues([
                    'title' => $article->getTitle(),
                    'description' => $article->getDescription(),
                    'content' => $article->getContent()
                ]);

                if ($articleForm->isSubmitted() && $articleForm->isValid()) {
                    $article->setTitle($_POST['title']);
                    $article->setDescription($_POST['description']);
                    $article->setContent($_POST['content']);
                    $article->setId($articleId); // Ensure the ID is set for updating
                    $article->save();

                    header('Location: /list-articles');
                    exit();
                }

                $view = new View("Article/editArticle");
                $view->assign('form', $articleForm->build());
                $view->render();
            } else {
                echo "Article non trouvé !";
            }
        } else {
            echo "ID article non spécifié !";
        }
    }


    public function delete(): void
    {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $articleId = intval($_GET['id']);
            $article = (new Article())->getArticleById($articleId);

            if ($article) {
                $article->delete();
                header('Location: /dashboard');
                exit();
            } else {
                echo "Article non trouvé !";
            }
        } else {
            echo "ID article non spécifié !";
        }
    }
}
