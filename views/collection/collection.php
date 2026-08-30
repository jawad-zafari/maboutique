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

    <div class="collection-header-box">
        <h2 class="main-title"><?= $this->e($pageTitle) ?></h2>
        <?php if ($type === 'latest'): ?>
            <p class="subtitle">Les derniers produits ajoutés à notre catalogue.</p>
        <?php elseif ($type === 'special'): ?>
            <p class="subtitle">Ne manquez pas ces produits à prix réduit.</p>
        <?php endif; ?>
    </div>

    <form id="collectionFilterForm" aria-label="Filtres de la collection">
        <div class="search-toolbar glass-panel">
            <label class="toggle-switch" title="Afficher uniquement les produits en stock">
                <input type="checkbox" id="toggleInStock" name="in_stock" value="1" <?= $inStock === 1 ? 'checked' : '' ?>>
                <span class="slider round"></span>
                <span class="toggle-label">En stock</span>
            </label>

            <select name="orderType1" class="form-control" aria-label="Trier par">
                <option value="3" <?= $order1 === 3 ? 'selected' : '' ?>>Plus récents</option>
                <option value="1" <?= $order1 === 1 ? 'selected' : '' ?>>Prix</option>
                <option value="2" <?= $order1 === 2 ? 'selected' : '' ?>>Vues</option>
            </select>
            
            <select name="orderType2" class="form-control" aria-label="Ordre du tri">
                <option value="2" <?= $order2 === 2 ? 'selected' : '' ?>>Décroissant</option>
                <option value="1" <?= $order2 === 1 ? 'selected' : '' ?>>Croissant</option>
            </select>
            
            <select name="limit" class="form-control" aria-label="Nombre de produits par page">
                <option value="20" <?= $limitVal === 20 ? 'selected' : '' ?>>20 / page</option>
                <option value="40" <?= $limitVal === 40 ? 'selected' : '' ?>>40 / page</option>
                <option value="60" <?= $limitVal === 60 ? 'selected' : '' ?>>60 / page</option>
            </select>
        </div>
    </form>

   