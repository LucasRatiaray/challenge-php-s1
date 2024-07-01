<?php

namespace App\Controller;

use App\Core\Security as Auth;
use App\Core\View;
use App\Models\Media;

class MediaController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }


        $security = new Auth();

        if (!$security->isLogged()) {
            echo "Vous devez être connecté";
            return;
        }

    }

    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    }

    public function index()
    {
        $images = (new Media())->findAll();
        $view = new View("Media/list", "front");
        $view->assign("images", $images);
        $view->render();
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);

            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0775, true)) {
                    die('Failed to create directories...');
                }
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $image = new Media();
                $image->setTitle($_POST['title']);
                $image->setDescription($_POST['description']);
                $image->setLien($targetFile);
                $image->save();
                header("Location: /media");
                exit();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $view = new View("Media/upload");
        $view->render();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $image = (new Media())->findOneById($_POST['id']);
            if ($image) {
                $filePath = $image->getLien();
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $image->delete();
                header("Location: /media");
                exit();
            } else {
                echo "Image not found.";
            }
        }
    }
}
