<?php

$exclusives = $data['exclusives'] ?? [];
?>

<div class="exclusive-section">
    <div class="exclusive-header">
        <h3><i class="fa-solid fa-star" aria-hidden="true"></i> Exclusivités Boutique</h3>
    </div>
    
    <div class="exclusive-carousel-wrapper" id="exclusiveCarousel">
        <button type="button" class="nav-btn prev" id="btnExclusivePrev" aria-label="Produits précédents">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>
        
        <div class="carousel-viewport" id="exclusiveViewport">
            <?php if (!empty($exclusives)): foreach ($exclusives as $row): 
                $exId = (int)($row['id'] ?? 0);
                $exTitle = htmlspecialchars($row['title'] ?? 'Produit', ENT_QUOTES, 'UTF-8');
                
                // RÈGLE MÉTIER : Affichage correct du prix avec remise (si calculé par le modèle)
                $exPrice = (float)($row['price_total'] ?? $row['price'] ?? 0);
                
                $thumbUrl = URL . 'public/images/products/' . $exId . '/product_220.jpg';
            ?>
                