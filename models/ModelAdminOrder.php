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

    public function bulkUpdateStatus(array $safeIds, int $statusId): void
    {
        if (empty($safeIds) || $statusId <= 0) return;
        
        // Utilisation de marqueurs dynamiques pour la sécurité de la requête
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "UPDATE orders SET status_id = ? WHERE id IN ($placeholders)";
        
        // Fusion du statut avec le tableau d'identifiants
        $params = array_merge([$statusId], $safeIds);
        $this->doQuery($sql, $params);
    }

    public function getOrderInfo(int $orderId): array
    {
        $sql = "SELECT o.*, pa.title as payTypeTitle, po.title as postTitle
                FROM orders o 
                LEFT JOIN payment_methods pa ON o.payment_method_id = pa.id
                LEFT JOIN shipping_methods po ON o.shipping_method_id = po.id
                WHERE o.id = ?";

        $result = $this->doSelect($sql, [$orderId], 'fetch');
        return is_array($result) ? $result : [];
    }

    public function editOrder(int $orderId, array $cleanData): void
    {
        // Le modèle reçoit des données déjà formatées par le contrôleur
        $sql = "UPDATE orders SET address_data = ?, postal_code = ?, phone = ?, is_paid = ?, status_id = ?, tracking_code = ?, admin_note = ? WHERE id = ?";
        
        $this->doQuery($sql, [
            $cleanData['address'], 
            $cleanData['postal_code'], 
            $cleanData['phone'], 
            $cleanData['pay_status'], 
            $cleanData['order_status'], 
            $cleanData['tracking_code'], 
            $cleanData['admin_note'], 
            $orderId
        ]);
    }

    public function orderStatus(): array
    {
        $result = $this->doSelect("SELECT * FROM order_statuses");
        return is_array($result) ? $result : [];
    }

    public function delete(array $safeIds): void
    {
        if (empty($safeIds)) return;
        
        // Les identifiants sont déjà sécurisés, on prépare simplement la requête
        $placeholders = rtrim(str_repeat('?,', count($safeIds)), ',');
        
        $sql = "DELETE FROM orders WHERE id IN ($placeholders)";
        $this->doQuery($sql, $safeIds);
    }
}
?>