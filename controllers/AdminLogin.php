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

   
}
?>