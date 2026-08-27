<?php

// Récupération sécurisée des variables transmises par le contrôleur global
$userId    = $userId ?? false;
$userLevel = $userLevel ?? 0;
$menuList  = $menuList ?? [];
$cartItems = $cartItems ?? [];
$cartCount = $cartCount ?? 0;
$favCount  = $favCount ?? 0;
$csrfToken = $csrf_token ?? '';
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <base href="<?= URL ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique en ligne</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= URL ?>public/assets/css/main.css">
</head>
<body>

<header class="site-header">
    <div class="header-container">
        
        <div class="logo-container">
            <a href="<?= URL ?>Index/index" aria-label="Page d'accueil">
                <img src="<?= URL ?>public/images/logo.png" alt="MaBoutique" class="site-logo">
            </a>
        </div>

       <div class="search-container">
            <form action="<?= URL ?>Search/index" method="POST" id="headerSearchForm" class="search-form-wrapper">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <input type="text" id="headerKeyword" name="keyword" class="search-input" placeholder="Rechercher un produit..." autocomplete="off" aria-label="Champ de recherche">
                <button type="submit" class="search-btn" aria-label="Lancer la recherche">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                </button>
            </form>
            
            <ul id="headerAutoSuggest" class="header-suggest-list" role="listbox"></ul>
        </div>

        <div class="header-actions">
            
            <?php if ((int)$userLevel === 1): ?>
                <a href="<?= URL ?>AdminDashboard/index" class="btn-icon" aria-label="Administration" title="Administration">
                    <i class="fa-solid fa-user-gear" aria-hidden="true"></i>
                </a>
            <?php endif; ?>

            <a href="<?= URL ?>Account/favorites" class="btn-icon" aria-label="Mes favoris" title="Mes favoris">
                <i class="fa-regular fa-heart" aria-hidden="true"></i>
                <span class="cart-counter <?= (int)$favCount === 0 ? 'is-hidden' : '' ?>" id="navFavCounterBadge"><?= (int)$favCount ?></span>
            </a>

            <?php if ($userId == false): ?>
                <a href="<?= URL ?>Login/index" class="btn-icon" aria-label="Se connecter ou s'inscrire" title="Se connecter / S'inscrire">
                    <i class="fa-solid fa-user" aria-hidden="true"></i>
                </a>
            <?php else: ?>
                <a href="<?= URL ?>Account/index" class="btn-icon" aria-label="Mon Espace Client" title="Mon Espace Client">
                    <i class="fa-solid fa-user" aria-hidden="true"></i>
                </a>
            <?php endif; ?>

            <a href="#" class="btn-icon cart-btn" aria-label="Voir mon panier" title="Mon panier">
                <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> 
                <span class="cart-counter" id="navCartCounterBadge"><?= (int)$cartCount ?></span>
            </a>

            <button class="btn-mobile-menu" id="btnToggleNav" aria-label="Ouvrir le menu de navigation">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
        </div>
        
    </div>
</header>

