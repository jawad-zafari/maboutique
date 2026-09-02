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

    <div class="admin-table-wrapper mt-25">
        <table class="admin-table" aria-label="Détail des commandes de la période sélectionnée">
            <thead>
                <tr>
                    <th scope="col" class="col-id text-center">N°</th>
                    <th scope="col">Date de création</th>
                    <th scope="col">Nom du client</th>
                    <th scope="col">Montant</th>
                    <th scope="col" class="text-center">Statut du paiement</th>
                    <th scope="col">Ville de livraison</th>
                    <th scope="col" class="text-center">Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($result)): foreach ($result as $row): 
                    $isPaid = (int)($row['is_paid'] ?? 0);
                ?>
                <tr>
                    <td class="text-center"><strong>#<?= (int)$row['id']; ?></strong></td>
                    // Échappement des données pour éviter les failles XSS
                    <td><?= $this->e($row['created_date'] ?? '') ?></td>
                    <td><?= $this->e($row['last_name'] ?? 'Client') ?></td>
                    <td><strong><?= number_format((float)($row['total_price'] ?? $row['amount'] ?? 0), 2, ',', ' '); ?> €</strong></td>
                    
                    <td class="text-center">
                        <span class="badge-payment <?= $isPaid === 1 ? 'paid' : 'unpaid' ?>">
                            <?= $isPaid === 1 ? 'Payé' : 'Non Payé' ?>
                        </span>
                    </td>
                    
                    <td><?= $this->e($row['city'] ?? '-') ?></td>
                    
                    <td class="text-center">
                        <a href="<?= URL ?>AdminOrder/detail/<?= (int)$row['id']; ?>" class="action-icon icon-list" title="Voir les détails de la commande" aria-label="Voir la commande <?= (int)$row['id']; ?>">
                            <i class="fa-solid fa-eye" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" class="text-empty-table text-center">Aucune commande trouvée pour cette période.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="<?= URL ?>public/assets/js/admin_statistics.js" defer></script>