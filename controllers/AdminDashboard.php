<?php

class AdminDashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Initialisation de session et autorisation
        Model::sessionInit();
        $userLevel = (int) Model::getUserLevel();
        
        // Seuls l'Administrateur (1) et l'Employé (2) peuvent y accéder
        if ($userLevel !== 1 && $userLevel !== 2) {
            header('Location: ' . URL . 'AdminLogin/index');
            exit; 
        }
    }

    public function index(): void
    {
        // Récupération des statistiques de commandes pour les 7 derniers jours
        $todayDate = date('Y-m-d');
        $lastWeekDate = date('Y-m-d', strtotime('-6 days'));
        
        // Création du tableau de référence avec des valeurs à 0
        $dates = $this->getRange($lastWeekDate, $todayDate);
        $orderStat = [];
        foreach ($dates as $date) {
            $orderStat[$date] = 0;
        }

        // Le contrôleur demande les données brutes au modèle
        $results = $this->model->getStat($lastWeekDate . ' 00:00:00');

        // Fusion des résultats SQL avec le tableau initialisé
        if (!empty($results) && is_array($results)) {
            foreach ($results as $row) {
                $orderDate = $row['order_date'];
                
                if (isset($orderStat[$orderDate])) {
                    $orderStat[$orderDate] = (int) $row['total_orders'];
                }
            }
        }

        $data = [
            'orderStat' => $orderStat
        ];
        
        // Chargement de la vue du tableau de bord
        $this->view('admin/admin_dashboard/dashboard', $data);    
    }

    // Génère un tableau contenant toutes les dates entre deux périodes (Logique métier)
    private function getRange(string $startDate, string $lastDate): array
    {
        $dates = [];
        $current = strtotime($startDate);
        $last = strtotime($lastDate);

        while ($current <= $last) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }
}
?>