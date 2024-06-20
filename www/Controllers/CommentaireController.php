<?php

namespace App\Controller;

use App\Core\View;
use App\Models\Commentaire;
use App\Forms\CommentaireForm;

class CommentaireController
{

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

    public function moderate()
    {
        echo "Modérer commentaire";
    }

    public function approve()
    {
        echo "Approuver commentaire";
    }

    public function delete()
    {
        echo "Supprimer commentaire";
    }
}
