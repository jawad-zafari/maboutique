<?php

class ModelAdminDashboard extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    
    //  Calcule les statistiques des commandes des 7 derniers jours.
    
    public function getStat()
    {
        $todayDate = date('Y-m-d');
        $lastWeekDate = date('Y-m-d', strtotime('-6 days'));
        
        // Initialisation du tableau avec des 0 pour chaque jour
        $dates = $this->getRange($lastWeekDate, $todayDate);
        $orderStat = [];
        foreach ($dates as $date) {
            $orderStat[$date] = 0;
        }

        // on compte directement dans la base de données
        $sql = "SELECT DATE(created_date) as order_date, COUNT(id) as total_orders 
                FROM orders 
                WHERE created_date >= ? 
                GROUP BY DATE(created_date)";
        
        // Ajout de l'heure pour correspondre au format datetime de la base de données
        $results = $this->doSelect($sql, [$lastWeekDate . ' 00:00:00']);

        // Fusion des résultats SQL avec notre tableau initialisé
        if (!empty($results)) {
            foreach ($results as $row) {
                $orderDate = $row['order_date'];
                // Si la date SQL existe dans notre plage de 7 jours, on met à jour la valeur
                if (isset($orderStat[$orderDate])) {
                    $orderStat[$orderDate] = (int)$row['total_orders'];
                }
            }
        }

        return $orderStat;
    }

    
    
}
?>