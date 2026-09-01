<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-gauge-high" aria-hidden="true"></i> Tableau de Bord
        </div>
    </header>

    <?php 
    // Encodage strict JSON pour JavaScript
    $orderStat = $data['orderStat'] ?? [];
    $keys = array_keys($orderStat);
    $values = array_values($orderStat);
    
    $jsonKeys = htmlspecialchars(json_encode($keys), ENT_QUOTES, 'UTF-8');
    $jsonValues = htmlspecialchars(json_encode($values), ENT_QUOTES, 'UTF-8');
    ?>

    <!-- Structure accessible pour les lecteurs d'écran -->
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
                    <td><?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int)$count ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

   