<?php
$cart = $data['cartData'][0] ?? [];
if (!is_array($cart)) { $cart = []; }

$totalProductsPrice = (float)($data['cartData'][1] ?? 0);
$totalDiscount = (float)($data['cartData'][2] ?? 0);
$addresses = $data['addresses'] ?? [];
$postTypes = $data['postType'] ?? [];
?>
