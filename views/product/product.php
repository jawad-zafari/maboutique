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

