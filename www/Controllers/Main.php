<?php

namespace App\Controller;

use App\Core\Security as Auth;
use App\Core\View;
use App\Models\Page;
use App\Models\User;
use App\Models\Article;
use App\Models\Commentaire;

class Main
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function home()
    {
        $view = new View("Main/home", "Back");
        $view->render();
    }

    public function logout()
    {
        // Logout logic
    }

    public function dashboard(): void
    {
        $security = new Auth();
        if (!$security->isLogged() || !$security->hasRole(['admin', 'author'])) {
            header("Location: /register");
            exit();
        }

        $pageModel = new Page();
        $userModel = new User();
        $articleModel = new Article();
        $commentaireModel = new Commentaire();

        $userId = $_SESSION['user_id'];
        $user = $userModel->getUserById($userId);

        $pages = $pageModel->getAllPages();
        $latestPage = $pageModel->getLatestPage();
        $latestArticle = $articleModel->getLatestArticle();
        $latestComment = $commentaireModel->getLatestComment();

        $totalUsers = $userModel->getCount();
        $totalPages = $pageModel->getCount();
        $totalArticles = $articleModel->getCount();
        $totalComments = $commentaireModel->getCount();

        $view = new View("Main/dashboard");
        $view->assign('pages', $pages);
        $view->assign('latestPage', $latestPage);
        $view->assign('latestArticle', $latestArticle);
        $view->assign('latestComment', $latestComment);
        $view->assign('totalUsers', $totalUsers);
        $view->assign('totalPages', $totalPages);
        $view->assign('totalArticles', $totalArticles);
        $view->assign('totalComments', $totalComments);
        $view->assign("userRole", $user['role']);
        $view->render();
    }

    public function customize()
    {
        $view = new View("Main/customize");
        $view->render();
    }
}
