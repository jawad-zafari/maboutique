<?php

class ModelCheckout extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Récupère les informations d'une commande (Sécurisé contre IDOR)
    public function getOrderInfo(int $orderId): array|false
    {
        self::sessionInit();
        $userId = (int)self::sessionGet('userId');

        if ($userId > 0) {
            $sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
            $result = $this->doSelect($sql, [$orderId, $userId], 'fetch', PDO::FETCH_ASSOC);
            
            return $result ?: false;
        }
        
        return false;
    }

    // Mise à jour du statut de la commande
    public function markOrderAsPaid(int $orderId, string $transactionId): bool
    {
        $sql = "UPDATE orders SET is_paid = 1, transaction_id_after = ? WHERE id = ?";
        $this->doQuery($sql, [$transactionId, $orderId]);
        return true;
    }

   
}
?>