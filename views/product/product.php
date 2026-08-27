<?php

// Récupération et sécurisation des données transmises par le contrôleur
$product = $data['productInfo'] ?? [];
$gallery = $data['gallery'] ?? [];
$csrfToken = $data['csrf_token'] ?? '';
$userId = Model::sessionGet('userId');

//  Récupération de l'état "Favori" calculé par le contrôleur 
$isFavorite = $data['isFavorite'] ?? false;

// Préparation de la galerie d'images (Image principale + images secondaires)
$allImages = [];
$productId = (int)($product['id'] ?? 0);
$mainImage = URL . 'public/images/products/' . $productId . '/product_350.jpg';
$allImages[] = [
    'url' => $mainImage, 
    'alt' => htmlspecialchars($product['title'] ?? 'Produit', ENT_QUOTES, 'UTF-8')
];

if (!empty($gallery)) {
    foreach ($gallery as $g) {
        $allImages[] = [
            'url' => URL . 'public/images/products/' . $productId . '/gallery/large/' . htmlspecialchars($g['image_name'], ENT_QUOTES, 'UTF-8'),
            'alt' => 'Galerie image ' . htmlspecialchars($product['title'] ?? '', ENT_QUOTES, 'UTF-8')
        ];
    }
}
?>

<div id="mainProductWrapper" class="product-page-container" data-csrf="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

    <nav class="breadcrumb-navigation" aria-label="Fil d'Ariane">
        <button type="button" class="btn-go-back js-back-button" aria-label="Retourner à la page précédente">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
        </button>
        <span class="divider-icon" aria-hidden="true">|</span>
        <a href="<?= URL ?>Index/index" class="link-home">
            <i class="fa-solid fa-house" aria-hidden="true"></i> Accueil
        </a>
        <i class="fa-solid fa-angle-right divider-icon" aria-hidden="true"></i>
        <span class="current-page-title" aria-current="page"><?= htmlspecialchars($product['title'] ?? 'Produit', ENT_QUOTES, 'UTF-8') ?></span>
    </nav>

    <div class="product-main-grid">
        
        <div class="product-gallery-column">
            <div class="main-image-preview-box">
                <img id="mainProductImageNode" src="<?= $mainImage ?>" alt="<?= htmlspecialchars($product['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="gallery-main-img">
                <div class="zoom-indicator-overlay" id="btnTriggerImageZoom" title="Agrandir l'image">
                    <i class="fa-solid fa-magnifying-glass-plus" aria-hidden="true"></i>
                </div>
            </div>
            
            <?php if (count($allImages) > 1): ?>
                <div class="gallery-thumbnails-row">
                    <?php foreach ($allImages as $index => $img): ?>
                        <div class="thumb-item-box <?= $index === 0 ? 'active' : '' ?>" data-src="<?= $img['url'] ?>">
                            <img src="<?= $img['url'] ?>" alt="<?= htmlspecialchars($img['alt'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        