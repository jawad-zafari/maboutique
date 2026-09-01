<?php

class AdminLogin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Model::sessionInit();
    }

    public function index()
    {
        // Valeur par défaut pour éviter l'erreur "rôle non défini" dans le header
        $userLevel = Model::getUserLevel();
        if ($userLevel === null) {
            $userLevel = 0;
        }

        // Redirection si l'utilisateur est déjà connecté en tant qu'admin (1) ou employé (2)
        if ($userLevel === 1 || $userLevel === 2) {
            header('Location: ' . URL . 'AdminDashboard/index');
            exit;
        }

        // PROTECTION CSRF : Génération du jeton
        $data = [
            'csrf_token' => $this->generateCsrfToken()
        ];

        $this->view('admin/admin_login/login', $data);
    }

    public function checkUser()
    {
        // Bloquer tout accès direct via GET
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            exit('Méthode non autorisée');
        }

        // Bloquer les requêtes malveillantes externes
        $this->checkCsrfToken($_POST['csrf_token'] ?? '');

        // Le modèle vérifie les identifiants et renvoie un statut
        $loginStatus = $this->model->checkUser($_POST);
        
        if ($loginStatus === 'locked') {
            // Trop de tentatives échouées : Redirection avec message de blocage
            header('Location: ' . URL . 'AdminLogin/index?error=locked');
            exit;
        } elseif ($loginStatus === true) {
            // Connexion réussie : Redirection sécurisée
            header('Location: ' . URL . 'AdminDashboard/index');
            exit;
        } else {
            // Échec standard : Redirection avec un paramètre d'erreur
            header('Location: ' . URL . 'AdminLogin/index?error=1');
            exit;
        }
    }

   
}
?>