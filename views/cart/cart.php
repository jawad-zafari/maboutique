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
                <div class="cart-product-card" data-row="<?= $currentRowId ?>">
                    
                    <div class="product-image-box">
                        <img src="<?= URL ?>public/images/products/<?= $productId ?>/product_220.jpg" 
                             alt="<?= $productTitle ?>" 
                             class="product-thumbnail-img"
                             onerror="this.src='https://placehold.co/100x100/f1f3f5/3b5bdb?text=Produit'">
                    </div>

                    <div class="product-details-box">
                        <h3 class="product-title"><?= $productTitle ?></h3>
                        
                        <?php if ($colorTitle): ?>
                            <p class="product-meta"><i class="fa-solid fa-palette" aria-hidden="true"></i> Couleur : <strong><?= $colorTitle ?></strong></p>
                        <?php endif; ?>

                        <?php if ($guaranteeTitle): ?>
                            <p class="product-meta"><i class="fa-solid fa-shield" aria-hidden="true"></i> Garantie : <strong><?= $guaranteeTitle ?></strong></p>
                        <?php endif; ?>

                        <div class="product-price-unit margin-top-sm">
                            Prix unitaire : <span><?= number_format($unitPrice, 2, ',', ' ') ?> €</span>
                        </div>
                    </div>

                    <div class="product-actions-box">
                        <div class="quantity-selector-modern">
                            <button type="button" class="btn-qty minus" data-row="<?= $currentRowId ?>" aria-label="Diminuer la quantité">-</button>
                            <input type="text" class="input-qty" value="<?= $currentQty ?>" readonly aria-label="Quantité" data-row="<?= $currentRowId ?>">
                            <button type="button" class="btn-qty plus" data-row="<?= $currentRowId ?>" aria-label="Augmenter la quantité">+</button>
                        </div>

                        <div class="product-total-price">
                            <?= number_format($totalPrice, 2, ',', ' ') ?> €
                        </div>

                        <button type="button" class="btn-remove-item" data-row="<?= $currentRowId ?>" aria-label="Supprimer cet article">
                            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                        </button>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>

            

</div>