<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-gauge-high" aria-hidden="true"></i> Tableau de Bord
        </div>
    </header>

    <?php 
    $orderStat = $data['orderStat'] ?? [];
    $keys = array_keys($orderStat);
    $values = array_values($orderStat);
    
    // Échappement des données pour éviter les attaques XSS
    $jsonKeys = $this->e(json_encode($keys) ?: '[]');
    $jsonValues = $this->e(json_encode($values) ?: '[]');
    ?>

    <!-- Structure accessible pour les lecteurs d'écran (Accessibilité) -->
    <div class="sr-only" aria-live="polite">
        <table aria-label="Données textuelles des statistiques de ventes des 7 derniers jours">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Nombre de commandes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orderStat as $date => $count): ?>
                <tr>
                    <!-- Échappement via e() -->
                    <td><?= $this->e($date) ?></td>
                    <td><?= (int)$count ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="dashboard-chart-wrapper">
        <div id="salesChartContainer" class="dashboard-chart"
             data-keys="<?= $jsonKeys ?>" 
             data-values="<?= $jsonValues ?>"
             aria-hidden="true">
        </div>
    </div>

</div>

<script src="https://code.highcharts.com/highcharts.js" defer></script>
<script src="https://code.highcharts.com/modules/exporting.js" defer></script>
<script src="<?= URL ?>public/assets/js/admin_dashboard.js" defer></script>