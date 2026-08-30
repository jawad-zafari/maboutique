<?php
// SÉCURITÉ : Initialisation sécurisée des variables passées par le contrôleur
$data        = $data ?? [];
$type        = $data['type'] ?? 'latest';
$products    = $data['products'] ?? [];
$currentPage = (int)($data['currentPage'] ?? 1);
$totalPages  = (int)($data['totalPages'] ?? 1);
$categoryId  = (int)($data['categoryId'] ?? 0);

$filters  = $data['filters'] ?? [];
$inStock  = (int)($filters['in_stock'] ?? 0);
$order1   = (int)($filters['order_type_1'] ?? 3);
$order2   = (int)($filters['order_type_2'] ?? 2);
$limitVal = (int)($filters['limit'] ?? 20);

