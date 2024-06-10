<?php

// Définir le chemin vers le fichier config.yml
$configFilePath = __DIR__ . '/../Config/config.yml';

if (file_exists($configFilePath)) {
    $config = yaml_parse_file($configFilePath);
} else {
    header("Internal Server Error", true, 500);
    die("Le fichier de configuration ../config.yml n'existe pas");
}

if (empty($config["smtp"])) {
    header("Internal Server Error", true, 500);
    die("Le fichier de configuration ../config.yml ne contient pas de configuration pour smtp");
}

if (empty($config["smtp"]["host"]) || empty($config["smtp"]["username"]) || empty($config["smtp"]["password"]) || empty($config["smtp"]["port"]) || empty($config["smtp"]["encryption"])) {
    header("Internal Server Error", true, 500);
    die("Le fichier de configuration ../config.yml ne contient pas de configuration complete pour smtp");
}

return $config;