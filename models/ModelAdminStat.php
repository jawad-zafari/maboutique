<?php

class ModelAdminStat extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function order(string $startDate, string $endDate): array
    {
        // Validation stricte des dates pour éviter les injections SQL
        if (empty($startDate) || empty($endDate)) {
            return [
                'result' => [], 
                'order_paied' => 0, 
                'startDate' => 'Invalide', 
                'endDate' => 'Invalide'
            ];
        }

        // Ajout des heures pour couvrir la journée complète
        $startDateTime = $startDate . ' 00:00:00';
        $endDateTime = $endDate . ' 23:59:59';

        // Requête SQL sécurisée pour récupérer les commandes entre les deux dates
        $sql = "SELECT * FROM orders WHERE created_date BETWEEN ? AND ? ORDER BY created_date DESC";
        $result = $this->doSelect($sql, [$startDateTime, $endDateTime]);
        
        $ordersPaid = 0;

        
}
?>