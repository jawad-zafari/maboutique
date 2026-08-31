<?php
$cart = $data['cartData'][0] ?? [];

if (!is_array($cart)) { 
    $cart = []; 
}

$totalProductsPrice = (float)($data['cartData'][1] ?? 0);
$totalDiscount = (float)($data['cartData'][2] ?? 0);
$shippingPrice = (float)($data['postPrice'] ?? 0);

$addressInfo = $data['addressInfo'] ?? [];
if (!is_array($addressInfo)) {
    $addressInfo = [];
}

$shippingType = (int)($data['postType'] ?? 1);
$finalTotal = $totalProductsPrice + $shippingPrice - $totalDiscount;
?>
