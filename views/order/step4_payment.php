<?php

$totalProductsPrice = (float)($data['cartData'][1] ?? 0);
$totalDiscount = (float)($data['cartData'][2] ?? 0);
$shippingPrice = (float)($data['postPrice'] ?? 0);
$finalTotal = max(0, $totalProductsPrice + $shippingPrice - $totalDiscount);
/** @var array $data */
$addressInfo = is_array($data['addressInfo'] ?? null) ? $data['addressInfo'] : [];
$csrfToken = $this->e($data['csrf_token'] ?? '');
?>
