<?php
class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // SÉCURITÉ : Initialisation de la session pour la gestion du jeton CSRF et de l'authentification
        Model::sessionInit(); 
    }

    //  Affiche la page de connexion

    public function index(): void
    {
        // Préparation du jeton CSRF pour le formulaire de connexion
        $data = [
            'csrf_token' => $this->generateCsrfToken()
        ];

        $this->view('login/login', $data);
    }

    
   
    
}
?>