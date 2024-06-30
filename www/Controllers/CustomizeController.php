<?php

namespace App\Controller;

use App\Core\View;
use App\Models\Config;

class CustomizeController
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showForm()
    {
        $configModel = new Config();
        $config = $configModel->getConfig();

        $view = new View("Customize/customize");
        $view->assign("config", $config);
        $view->render();
    }

    public function updateConfig()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $configModel = new Config();
            $config = $configModel->getConfig();

            $config->setBackgroundColor($_POST['background-color']);
            $config->setFontColor($_POST['font-color']);
            $config->setFontSize($_POST['font-size'] . 'px');
            $config->setFontStyle($_POST['font-style']);

            if ($config->updateConfig()) {
                header("Location: /customize");
                exit();
            } else {
                echo "There was an error updating the configuration.";
            }
        }
    }
}
