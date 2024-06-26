<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Form;
use App\Models\Article;
use App\Forms\CommentaireForm;
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

        $view = new View("Article/viewArticle");
        $view->assign("article", $article);
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

    public function edit()
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

        $form = new Form("EditArticle");
        $view = new View("Article/editArticle");
        $view->assign("form", $form->build($article)); // Pré-remplir le formulaire avec les données de l'article
        $view->assign("articleId", $articleId);
        $view->render();
    }
    
    public function update()
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

        $form = new Form("EditArticle");

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
            $article->setDescription($_POST['description'] ?? null);

            if ($article->update()) {
                header("Location: /view-article?id=" . $articleId);
                exit();
            } else {
                echo "There was an error updating the article.";
            }
        } else {
            echo "There was an error updating the article.";
            foreach ($form->getErrors() as $error) {
                echo "<p>$error</p>";
            }
        }
    }
    
    public function delete()
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
        if ($article->delete()) {
            header("Location: /list-articles");
            exit();
        } else {
            echo "Failed to delete the article.";
        }
    }

    
}
