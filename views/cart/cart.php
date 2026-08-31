<?php

$cartItems = $data['cartItems'] ?? [];
$totalPriceAll = (float)($data['priceTotalAll'] ?? 0);
$csrfToken = $data['csrf_token'] ?? '';
?>
<div id="mainCart" class="cart-modern-container" data-csrf="<?= $this->e($csrfToken) ?>">
    
    <div class="cart-header-main">
        <h2><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Mon Panier d'Achats</h2>
    </div>

    <?php if (!empty($cartItems) && is_array($cartItems)): ?>
        <div class="cart-grid-layout">
            
            <div class="cart-items-column">
                <?php foreach ($cartItems as $row): 
                    $currentRowId = (int)($row['cartRow'] ?? 0);
                    $currentQty = (int)($row['quantity'] ?? 1);
                    $unitPrice = (float)($row['price'] ?? 0);
                    $totalPrice = $unitPrice * $currentQty;
                    $productId = (int)($row['id'] ?? 0);
                    
                    // SÉCURITÉ : Nettoyage automatique des données avant affichage
                    $productTitle = $this->e($row['title'] ?? 'Produit');
                    $colorTitle = !empty($row['colorTitle']) ? $this->e($row['colorTitle']) : null;
                    $guaranteeTitle = !empty($row['garanteeTitle']) ? $this->e($row['garanteeTitle']) : null;
                ?>
               