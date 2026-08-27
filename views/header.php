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

        