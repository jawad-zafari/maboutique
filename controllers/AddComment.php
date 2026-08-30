<?php

class AddComment extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // SÉCURITÉ : Vérification de l'authentification
        Model::sessionInit();
        $userId = (int) Model::sessionGet('userId');

        if ($userId === 0) {
            header('Location: ' . URL . 'Login/index');
            exit;
        }
    }

   
}
?>