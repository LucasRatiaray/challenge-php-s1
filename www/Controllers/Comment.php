<?php

namespace Controllers;

class CommentController
{
    public function store()
    {
        echo "Enregistrer commentaire";
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