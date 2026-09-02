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
<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-chart-pie" aria-hidden="true"></i> Statistiques du 
            <!-- SÉCURITÉ : Échappement des dates pour éviter les failles XSS -->
            <strong class="text-highlight"><?= $this->e($stat['startDate'] ?? '') ?></strong> au 
            <strong class="text-highlight"><?= $this->e($stat['endDate'] ?? '') ?></strong>
        </div>
        <div class="admin-actions">
            <a href="#" class="btn-admin-back js-back-button" aria-label="Retourner au formulaire de filtre">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour aux filtres
            </a>
        </div>
    </header>

   
    </div>

</div>

<script src="<?= URL ?>public/assets/js/admin_statistics.js" defer></script>