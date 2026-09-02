<?php

class AdminSetting extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Vérification stricte des droits d'accès
        Model::sessionInit();
        $userLevel = (int) Model::getUserLevel();
        if ($userLevel !== 1) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit;
        }
    }

   
}
?>