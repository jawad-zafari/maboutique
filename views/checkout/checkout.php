<?php

$orderInfo = $data['orderInfo'] ?? [];
$orderId = (int)($orderInfo['id'] ?? 0);
$csrfToken = $this->e($data['csrf_token'] ?? '');

//Protection contre l'injection d'objets via unserialize
$basketProducts = !empty($orderInfo['cart_data']) ? unserialize($orderInfo['cart_data'], ['allowed_classes' => false]) : [];

$isPaid = !empty($orderInfo['is_paid']); 
$paymentMethodId = (int)($orderInfo['payment_method_id'] ?? 1); 
    
// Logique conditionnelle pour lancer la simulation si c'est un paiement en ligne non effectué
$isPendingOnlinePayment = (!$isPaid && $paymentMethodId === 1);

// Calculs financiers sécurisés
$subTotal = 0;
if (is_array($basketProducts)) {
    foreach ($basketProducts as $item) {
        $qty = (int)($item['quantity'] ?? 1);
        $price = (float)($item['price'] ?? 0);
        $subTotal += ($price * $qty);
    }
}
$shippingCost = (float)($orderInfo['shipping_price'] ?? 0);
$totalPayable = (float)($orderInfo['total_amount'] ?? ($subTotal + $shippingCost));
?>
