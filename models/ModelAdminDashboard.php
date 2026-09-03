<?php

class ModelAdminDashboard extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    // Récupère les statistiques de commandes à partir d'une date donnée
    public function getStat(string $startDate): array
    {
        $sql = "SELECT DATE(created_date) as order_date, COUNT(id) as total_orders 
                FROM orders 
                WHERE created_date >= ? 
                GROUP BY DATE(created_date)";
        
        $results = $this->doSelect($sql, [$startDate]);
        
        return is_array($results) ? $results : [];
    }
}
?>