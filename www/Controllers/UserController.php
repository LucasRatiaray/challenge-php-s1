<?php

namespace App\Controllers;

class UserController
{
    public function index()
    {
        echo "Liste des utilisateurs";
    }

    public function create()
    {
        echo "Créer utilisateur";
    }

    public function store()
    {
        echo "Enregistrer utilisateur";
    }

    public function edit($id)
    {
        echo "Modifier utilisateur";
    }

    public function update($id)
    {
        echo "Mettre à jour utilisateur";
    }

    public function delete($id)
    {
        echo "Supprimer utilisateur";
    }

    public function validateEmail($token)
    {
        // Validation de l'email
        // Implement your logic here
    }

    public function sendConfirmationEmail()
    {
        // Envoie d'email de confirmation
        // Implement your logic here
    }
}