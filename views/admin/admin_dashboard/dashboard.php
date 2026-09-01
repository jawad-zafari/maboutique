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

   