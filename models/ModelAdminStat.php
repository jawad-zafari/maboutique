<?php

class ModelAdminStat extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Récupère les commandes entre deux dates
    public function order(string $startDateTime, string $endDateTime): array
    {
        if (empty($startDateTime) || empty($endDateTime)) {
            return [];
        }

        // Requête SQL sécurisée avec des paramètres préparés
        $sql = "SELECT * FROM orders WHERE created_date BETWEEN ? AND ? ORDER BY created_date DESC";
        $result = $this->doSelect($sql, [$startDateTime, $endDateTime]);
        
        return is_array($result) ? $result : [];
    }
}
?>