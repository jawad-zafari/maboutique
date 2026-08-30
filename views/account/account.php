<?php 
//  Récupération et protection des données transmises par le contrôleur
$userInfo         = $userInfo ?? [];
$userName         = !empty($userInfo['username']) ? $userInfo['username'] : (!empty($userInfo['last_name']) ? $userInfo['last_name'] : 'Utilisateur');
$userEmail        = $userInfo['email'] ?? '';
$userInitial      = strtoupper(mb_substr($userName, 0, 1, 'UTF-8'));

$ordersList       = $orders ?? [];
$totalOrdersCount = (int)($totalOrdersCount ?? count($ordersList));
$totalSpentAmount = (float)($totalSpent ?? 0);
$latestOrder      = $latestOrder ?? ($ordersList[0] ?? null);
?>

