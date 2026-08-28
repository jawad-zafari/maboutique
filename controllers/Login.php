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

    
    //  Traite l soumission du formulaire de connexion
     
    public function checkUser(): void
    {
        // SÉCURITÉ : Bloquer les requêtes qui ne sont pas envoyées en POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit;
        }

       
}
?>