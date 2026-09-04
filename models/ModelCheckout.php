<?php

class ModelCheckout extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // variables propres et fortement typées
    public function getOrderInfo(int $orderId, int $userId): array|false
    {
        if ($userId > 0) {
            $sql = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
            $result = $this->doSelect($sql, [$orderId, $userId], 'fetch', PDO::FETCH_ASSOC);
            
            return $result ?: false;
        }
        
        return false;
    }

    public function markOrderAsPaid(int $orderId, string $transactionId): bool
    {
        $sql = "UPDATE orders SET is_paid = 1, transaction_id_after = ? WHERE id = ?";
        $this->doQuery($sql, [$transactionId, $orderId]);
        return true;
    }

    // variables propres et fortement typées
    public function updateCreditCard(string $creditCard, string $bank, int $day, int $month, int $year, int $orderId): void
    {
        $sql = "UPDATE orders SET pay_card_number = ?, pay_bank_name = ?, pay_day = ?, pay_month = ?, pay_year = ? WHERE id = ?";
        $this->doQuery($sql, [$creditCard, $bank, $day, $month, $year, $orderId]);
    }
}
?>