<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\Security as Auth;
use App\Models\Commentaire;

class CommentaireController


{    private $security;


    public function __construct()
    { $this->security = new Security();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $security = new Auth();

        if (!$security->isLogged()) {
            echo "Vous devez être connecté";
            return;
        }    }


    public function store()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_GET['article_id']) || !is_numeric($_GET['article_id'])) {
                echo "Invalid article ID.";
                return;
            }

            if (!isset($_SESSION['user_id'])) {
                echo "User not logged in.";
                return;
            }

            $comment = new Commentaire();
            $comment->setArticleId((int)$_GET['article_id']);
            $comment->setUserId((int)$_SESSION['user_id']);
            $comment->setContent($_POST['content']);

            if ($comment->save()) {
                header("Location: /view-article?id=" . $_GET['article_id']);
                exit();
            } else {
                echo "There was an error saving the comment.";
            }
        }
    }


    public function report()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $comment = (new Commentaire())->findOneById($_POST['id']);
            if ($comment) {
                if ($comment->report()) {
                    header("Location: /view-article?id=" . $comment->getArticleId());
                    exit();
                } else {
                    echo "Error reporting comment.";
                }
            } else {
                echo "Comment not found.";
            }
        }
    }


    public function delete()
    {

        if (!$this->security->hasRole(['admin', 'editor'])) {
            echo "Vous n'avez pas les droits nécessaires pour supprimer cet article.";
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
                echo "Invalid comment ID.";
                return;
            }

            if (!isset($_POST['article_id']) || !is_numeric($_POST['article_id'])) {
                echo "Invalid article ID.";
                return;
            }

            $comment = (new Commentaire())->findOneById((int)$_POST['id']);
            if ($comment) {
                if ($comment->delete()) {
                    header("Location: /view-article?id=" . $_POST['article_id']);
                    exit();
                } else {
                    echo "There was an error deleting the comment.";
                }
            } else {
                echo "Comment not found.";
            }
        }
    }

    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    }

}
