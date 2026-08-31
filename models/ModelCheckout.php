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

    
}
?>