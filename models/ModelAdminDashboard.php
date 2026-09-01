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

       
    }
}
?>