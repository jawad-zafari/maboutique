<div class="collection-container mt-30">
    
    <div class="breadcrumb-navigation">
        <button type="button" class="btn-go-back js-back-button" aria-label="Retourner à la page précédente">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
        </button>
        <span class="divider-icon">|</span>
        
        <a href="<?= URL ?>Index/index" class="link-home">
            <i class="fa-solid fa-house" aria-hidden="true"></i> Accueil
        </a>
        <i class="fa-solid fa-angle-right divider-icon" aria-hidden="true"></i>
        
        <a href="<?= URL ?>Account/index" class="link-home">
            <i class="fa-solid fa-user" aria-hidden="true"></i> Mon Compte
        </a>
        <i class="fa-solid fa-angle-right divider-icon" aria-hidden="true"></i>
        
        <span class="current-page-title">Mes Favoris</span>
    </div>

    <div class="collection-header-box">
        <h2 class="main-title"><i class="fa-solid fa-heart title-icon-danger" aria-hidden="true"></i> Mes produits favoris</h2>
        <p class="subtitle">Retrouvez ici tous les produits que vous avez sauvegardés.</p>
    </div>

    <div class="products-grid-layout">
        <?php 
        $favoritesList = $favorites ?? [];
        if (!empty($favoritesList)):
            foreach ($favoritesList as $product):
                $discount = (int)($product['discount_percent'] ?? 0);
                $hasDiscount = $discount > 0;
                $productId = (int)($product['id'] ?? 0);
                // SÉCURITÉ : Échappement des titres contre XSS
                $productTitle = $this->e($product['title'] ?? '');
        ?>
            