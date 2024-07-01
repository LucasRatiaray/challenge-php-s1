<?php

namespace App\Core;

class Form
{
    private $config;
    private $errors = [];

    public function __construct(String $name)
    {
        if (!file_exists("../Forms/" . $name . ".php")) {
            die("Le form " . $name . ".php n'existe pas dans le dossier ../Forms");
        }
        include "../Forms/" . $name . ".php";
        $name = "App\\Forms\\" . $name;
        $this->config = $name::getConfig();
    }

    public function build(): string
    {
        $html = "";

        if (!empty($this->errors)) {
            $html .= "<ul>";
            foreach ($this->errors as $error) {
                $html .= "<li>" . $error . "</li>";
            }
            $html .= "</ul>";
        }

        $html .= "<form action='" . $this->config["config"]["action"] . "' method='" . $this->config["config"]["method"] . "' enctype='multipart/form-data'>";

        foreach ($this->config["inputs"] as $name => $input) {
            $value = isset($input["value"]) ? $input["value"] : "";
            $html .= "<div class='input-field'>";
            if ($input["type"] == "select") {
                $html .= "<label for='$name'>" . $input["placeholder"] . "</label>";
                $html .= "<select name='" . $name . "' id='$name'>";
                $html .= "<option value='' disabled selected>" . $input["placeholder"] . "</option>";
                foreach ($input["options"] as $optionValue => $optionText) {
                    $selected = $value == $optionValue ? "selected" : "";
                    $html .= "<option value='" . htmlspecialchars($optionValue) . "' $selected>" . htmlspecialchars($optionText) . "</option>";
                }
                $html .= "</select>";
            } else {
                $html .= "
                <label for='$name'>" . $input["placeholder"] . "</label>
                <input 
                    type='" . $input["type"] . "' 
                    name='" . $name . "' 
                    id='" . $name . "'
                    value='" . htmlspecialchars($value) . "' 
                    " . (isset($input["required"]) && $input["required"] ? "required" : "") . "
                ><br>
                ";
            }
            $html .= "</div>";
        }

        $html .= "<button class='btn' type='submit'>" . htmlentities($this->config["config"]["submit"]) . "</button>";
        $html .= "</form>";

        return $html;
    }

    public function setValues(array $values): void
    {
        foreach ($values as $key => $value) {
            if (isset($this->config["inputs"][$key])) {
                $this->config["inputs"][$key]['value'] = $value;
            }
        }
    }

    public function isSubmitted(): bool
    {
        if ($this->config["config"]["method"] == "POST" && !empty($_POST)) {
            return true;
        } elseif ($this->config["config"]["method"] == "GET" && !empty($_GET)) {
            return true;
        } else {
            return false;
        }
    }

    public function isValid(): bool
    {
        // Est-ce que j'ai exactement le même nb de champs
        if (count($this->config["inputs"]) != count($_POST)) {
            $this->errors[] = "Tentative de Hack";
        }

        foreach ($_POST as $name => $dataSent) {
            // Est-ce qu'il s'agit d'un champ que je lui ai donné ?
            if (!isset($this->config["inputs"][$name])) {
                $this->errors[] = "Tentative de Hack, le champ " . $name . " n'est pas autorisé";
            }

            // Est-ce que ce n'est pas vide si required
            if (isset($this->config["inputs"][$name]["required"]) && $this->config["inputs"][$name]["required"] && empty($dataSent)) {
                $this->errors[] = "Le champ " . $name . " ne doit pas être vide";
            }

            // Est-ce que le min correspond
            if (isset($this->config["inputs"][$name]["min"]) && strlen($dataSent) < $this->config["inputs"][$name]["min"]) {
                $this->errors[] = $this->config["inputs"][$name]["error"];
            }

            // Est-ce que le max correspond
            if (isset($this->config["inputs"][$name]["max"]) && strlen($dataSent) > $this->config["inputs"][$name]["max"]) {
                $this->errors[] = $this->config["inputs"][$name]["error"];
            }

            // Est-ce que la confirmation correspond
            if (isset($this->config["inputs"][$name]["confirm"]) && $dataSent != $_POST[$this->config["inputs"][$name]["confirm"]]) {
                $this->errors[] = $this->config["inputs"][$name]["error"];
            } else {
                // Est-ce que le format email est OK
                if ($this->config["inputs"][$name]["type"] == "email" && !filter_var($dataSent, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = "Le format de l'email est incorrect";
                }
                // Est-ce que le format password est OK
                if ($this->config["inputs"][$name]["type"] == "password" && (!preg_match("#[a-z]#", $dataSent) || !preg_match("#[A-Z]#", $dataSent) || !preg_match("#[0-9]#", $dataSent))) {
                    $this->errors[] = $this->config["inputs"][$name]["error"];
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}