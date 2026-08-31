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

    // Mise à jour des informations pour le virement bancaire
    public function updateCreditCard(array $cleanData, int $orderId): void
    {
        $day        = $cleanData['day'] ?? 0;
        $month      = $cleanData['month'] ?? 0;
        $year       = $cleanData['year'] ?? 0;
        $creditCard = $cleanData['creditcard'] ?? '';
        $bank       = $cleanData['bank'] ?? '';

        // Insertion directe (les données ont été nettoyées via strip_tags dans le contrôleur)
        $sql = "UPDATE orders SET pay_card_number = ?, pay_bank_name = ?, pay_day = ?, pay_month = ?, pay_year = ? WHERE id = ?";
        $this->doQuery($sql, [$creditCard, $bank, $day, $month, $year, $orderId]);
    }
}
?>