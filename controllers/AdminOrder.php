<?php

class AdminOrder extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Vérification des droits d'accès avec typage strict
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        
        // Seul l'administrateur (Niveau 1) peut y accéder
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

    
}
?>