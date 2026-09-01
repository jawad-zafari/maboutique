<?php
$productInfo = $data['productInfo'] ?? [];
$reviewInfo = $data['naghdInfo'] ?? [];
$isEdit = isset($reviewInfo['title']);
$pId = (int)($productInfo['id'] ?? 0);
$rId = (int)($reviewInfo['id'] ?? 0);
?>
