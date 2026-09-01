<?php 
$orderInfo = $data['orderInfo'] ?? []; 
// SÉCURITÉ CRITIQUE : Prévention des attaques PHP Object Injection via unserialize
$cart = !empty($orderInfo['cart_data']) ? unserialize($orderInfo['cart_data'], ['allowed_classes' => false]) : [];

$subTotal = 0;
foreach ($cart as $row) {
    $qty = (int)($row['quantity'] ?? 1);
    $subTotal += ((float)($row['price'] ?? 0)) * $qty;
}
$shippingPrice = (float)($orderInfo['shipping_price'] ?? $orderInfo['post_price'] ?? 0);
$totalAmount = (float)($orderInfo['total_amount'] ?? $orderInfo['total_price'] ?? $orderInfo['amount'] ?? 0);

$invoiceDate = date('Y-m-d');
if (!empty($orderInfo['created_timestamp'])) {
    $invoiceDate = date('Y-m-d', $orderInfo['created_timestamp']);
} elseif (!empty($orderInfo['created_date'])) {
    $invoiceDate = $orderInfo['created_date'];
}
$orderId = (int)($orderInfo['id'] ?? 0);
?>
