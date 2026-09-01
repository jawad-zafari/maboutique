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

    public function editOrder(int $orderId, array $data): void
    {
        // Les données sont stockées brutes. PDO empêche l'injection SQL.
        $address = trim($data['address'] ?? '');
        $postalCode = trim($data['postal_code'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $trackingCode = trim($data['tracking_code'] ?? '');
        $adminNote = trim($data['admin_note'] ?? '');
        
        // Typage strict
        $payStatus = (int)($data['pay_status'] ?? 0);
        $orderStatus = (int)($data['order_status'] ?? 1);

        $sql = "UPDATE orders SET address_data = ?, postal_code = ?, phone = ?, is_paid = ?, status_id = ?, tracking_code = ?, admin_note = ? WHERE id = ?";
        
        $this->doQuery($sql, [$address, $postalCode, $phone, $payStatus, $orderStatus, $trackingCode, $adminNote, $orderId]);
    }

    public function orderStatus(): array
    {
        $result = $this->doSelect("SELECT * FROM order_statuses");
        return is_array($result) ? $result : [];
    }

   
}
?>