<?php

class AdminCategory extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Vérification stricte des droits d'accès
        Model::sessionInit();
        $level = (int) Model::getUserLevel();
        
        // Seul l'administrateur (niveau 1) peut accéder à ce contrôleur
        if ($level !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

   
}
?>