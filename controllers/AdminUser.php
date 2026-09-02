<?php

class AdminUser extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Seul l'administrateur principal (Niveau 1) peut gérer les utilisateurs
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

   
}
?>