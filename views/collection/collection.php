<?php
// SÉCURITÉ : Initialisation sécurisée des variables passées par le contrôleur
$data        = $data ?? [];
$type        = $data['type'] ?? 'latest';
$products    = $data['products'] ?? [];
$currentPage = (int)($data['currentPage'] ?? 1);
$totalPages  = (int)($data['totalPages'] ?? 1);
$categoryId  = (int)($data['categoryId'] ?? 0);

$filters  = $data['filters'] ?? [];
$inStock  = (int)($filters['in_stock'] ?? 0);
$order1   = (int)($filters['order_type_1'] ?? 3);
$order2   = (int)($filters['order_type_2'] ?? 2);
$limitVal = (int)($filters['limit'] ?? 20);

// Définition du titre de la page
$pageTitle = "Collection";
if ($type === 'latest') { $pageTitle = "Nouveautés"; }
elseif ($type === 'special') { $pageTitle = "Offres du moment"; }
elseif ($type === 'exclusive') { $pageTitle = "Exclusivités Boutique"; }
elseif ($type === 'mostviewed') { $pageTitle = "Les plus vus"; }
elseif ($type === 'category') { $pageTitle = $data['categoryTitle'] ? $data['categoryTitle'] : "Catégorie"; }
?>

<!--Stockage du jeton CSRF pour les requêtes AJAX (ex: Ajout au panier) -->
<div id="collectionMainWrapper" class="collection-container collection-page-wrapper" data-csrf="<?= $this->e($data['csrf_token'] ?? '') ?>">
    
    <nav class="breadcrumb-navigation" aria-label="Fil d'Ariane">
        <button type="button" class="btn-go-back js-back-button" aria-label="Retour à la page précédente">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
        </button>
        <span class="divider-icon" aria-hidden="true">|</span>
        <a href="<?= URL ?>Index/index" class="link-home">
            <i class="fa-solid fa-house" aria-hidden="true"></i> Accueil
        </a>
        <i class="fa-solid fa-angle-right divider-icon" aria-hidden="true"></i>
        <!-- SÉCURITÉ (XSS) : Affichage sécurisé du titre -->
        <span class="current-page-title" aria-current="page"><?= $this->e($pageTitle) ?></span>
    </nav>

    