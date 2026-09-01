<?php

class ModelAdminOrder extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOrders(): array
    {
        $sql = "SELECT o.*, os.title as statusTitle 
                FROM orders o 
                LEFT JOIN order_statuses os ON o.status_id = os.id 
                ORDER BY o.id DESC";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function bulkUpdateStatus(array $ids, int $statusId): void
    {
        if (empty($ids) || empty($statusId)) return;
        
        // Utilisation de placeholders dynamiques pour la clause IN
        $safeIds = array_map('intval', $ids);
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "UPDATE orders SET status_id = ? WHERE id IN ($placeholders)";
        
        // Fusion du statusId avec le tableau d'IDs pour l'exécution PDO
        $params = array_merge([$statusId], $safeIds);
        $this->doQuery($sql, $params);
    }

   
}
?>