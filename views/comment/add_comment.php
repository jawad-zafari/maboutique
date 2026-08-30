<?php

$productInfo   = $data['productInfo'] ?? [];
$commentInfo   = $data['commentInfo'] ?? [];
$params        = $data['params'] ?? [];
$commentParams = $data['commentParams'] ?? [];
$csrfToken     = $data['csrf_token'] ?? '';
$productId     = (int)($productInfo['id'] ?? 0);
?>

