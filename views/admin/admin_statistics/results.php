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

    <div class="stats-summary-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-shopping-cart"></i></div>
            <div class="stat-content">
                <span class="stat-title">Total des commandes</span>
                <span class="stat-value"><?= $totalOrders ?></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success"><i class="fa-solid fa-check-circle"></i></div>
            <div class="stat-content">
                <span class="stat-title">Commandes payées</span>
                <span class="stat-value"><?= $paiedOrders ?> <small>(<?= $paiedPercentage ?>%)</small></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon primary"><i class="fa-solid fa-euro-sign"></i></div>
            <div class="stat-content">
                <span class="stat-title">Chiffre d'affaires</span>
                <span class="stat-value"><?= number_format($totalRevenue, 2, ',', ' ') ?> €</span>
            </div>
        </div>
    </div>

    

</div>

<script src="<?= URL ?>public/assets/js/admin_statistics.js" defer></script>