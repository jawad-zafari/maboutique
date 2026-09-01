<?php

class AdminDashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Initialisation de session et autorisation uniquement pour Admin (1) et Employé (2)
        Model::sessionInit();
        $userLevel = Model::getUserLevel();
        
        if ($userLevel !== 1 && $userLevel !== 2) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit; 
        }
    }

    public function index()
    {
        // Récupération des statistiques optimisées depuis la base de données
        $orderStatistics = $this->model->getStat();
        
        $data = [
            'orderStat' => $orderStatistics
        ];
        
           
    }
}
?>