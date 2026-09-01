<?php 
$orderInfo = $data['orderInfo'] ?? []; 
// Prévention des attaques PHP Object Injection via unserialize
$cart = !empty($orderInfo['cart_data']) ? unserialize($orderInfo['cart_data'], ['allowed_classes' => false]) : [];
$orderId = (int)($orderInfo['id'] ?? 0);
?>
