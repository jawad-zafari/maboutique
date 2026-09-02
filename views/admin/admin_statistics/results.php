<?php
$stat = $data['stat'] ?? [];
$result = $stat['result'] ?? [];
$totalOrders = count($result);
$paiedOrders = (int)($stat['order_paied'] ?? 0);
$paiedPercentage = $totalOrders > 0 ? round(($paiedOrders / $totalOrders) * 100, 2) : 0;

// Calcul du chiffre d'affaires total pour les commandes payées
$totalRevenue = 0;
foreach ($result as $row) {
    if (isset($row['is_paid']) && (int)$row['is_paid'] === 1) {
        $totalRevenue += (float)($row['total_price'] ?? $row['amount'] ?? 0);
    }
}
?>
