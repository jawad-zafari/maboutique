<?php

class Register extends Controller 
{
    public function __construct() 
    {
        parent::__construct();
        // SÉCURITÉ : Initialisation de la session pour la gestion du jeton CSRF
        Model::sessionInit(); 
    }
    
    
    //  Afficher la page du formulaire d'inscription
    
    public function index(): void 
    {
        $data = [
            // PROTECTION CSRF : Génération du jeton sécurisé
            'csrf_token' => $this->generateCsrfToken()
        ];
        
        $this->view('register/register', $data);
    }

    
   
}
?>