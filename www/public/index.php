<?php

namespace App;

use App\Core\Error;

// Autoloader
require_once "../vendor/autoload.php";

// Routing
$uri = $_SERVER["REQUEST_URI"];
if (strlen($uri) > 1)
    $uri = rtrim($uri, "/");
$uriExploded = explode("?", $uri);
$uri = $uriExploded[0];

// Routes
if (file_exists("../Routes.yml")) {
    $listOfRoutes = yaml_parse_file("../Routes.yml");
} else {
    $error = new Core\Error();
    $error->customError(500, "Le fichier routes.yml n'existe pas");
}

if (empty($listOfRoutes[$uri])) {
    $error = new Error();
    $error->customError(404, "Page 404 Not Found");
}

if (empty($listOfRoutes[$uri]["Controller"]) || empty($listOfRoutes[$uri]["Action"])) {
    $error = new Error();
    $error->customError(500, "Le fichier routes.yml ne contient pas de controller ou d'action pour l'uri :" . $uri);
}

// 
$controller = $listOfRoutes[$uri]["Controller"];
$action = $listOfRoutes[$uri]["Action"];

//
if (!file_exists("../Controllers/" . $controller . ".php")) {
    die("Le fichier controller ../Controllers/" . $controller . ".php n'existe pas");
}

include "../Controllers/" . $controller . ".php";
$controller = "App\\Controller\\" . $controller;

if (!class_exists($controller)) {
    die("La class controller " . $controller . " n'existe pas");
}
$objetController = new $controller();

if (!method_exists($controller, $action)) {
    die("Le methode " . $action . " n'existe pas dans le controller " . $controller);
}

$objetController->$action();
