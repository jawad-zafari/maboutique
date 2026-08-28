<?php
class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // SÉCURITÉ : Initialisation de la session pour la gestion du jeton CSRF et de l'authentification
        Model::sessionInit(); 
    }

    
    
}
?>