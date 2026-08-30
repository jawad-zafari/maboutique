<?php

$sideFilters = $data['attrRight'] ?? [];
$colors      = $data['colors'] ?? [];
$topFilters  = $data['attr'] ?? [];
$categoryId  = (int)($data['categoryId'] ?? 0);
$keyword     = $data['keyword'] ?? '';
$csrfToken   = $data['csrf_token'] ?? '';
?>
<div class="search-container">
    
    <form id="searchForm" action="<?= URL ?>Search/doSearch" method="post" aria-label="Formulaire de recherche et de filtrage">
        
        <input type="hidden" name="categoryId" value="<?= $categoryId ?>">
        <input type="hidden" name="keyword" value="<?= $this->e($keyword) ?>">
        
        <input type="hidden" id="globalCsrfToken" name="csrf_token" value="<?= $this->e($csrfToken) ?>">
        
        <div class="search-toolbar glass-panel">
            
            <label class="toggle-switch" title="Afficher uniquement les produits en stock">
                <input type="checkbox" id="toggleInStock" name="in_stock" value="1" aria-label="Afficher uniquement les produits en stock">
                <span class="slider round" aria-hidden="true"></span>
                <span class="toggle-label">En stock</span>
            </label>

            <select name="orderType1" class="form-control" aria-label="Critère de tri principal">
                <option value="3">Plus récents</option>
                <option value="1">Prix</option>
                <option value="2">Vues</option>
            </select>
            
            <select name="orderType2" class="form-control" aria-label="Ordre du tri">
                <option value="2">Décroissant</option>
                <option value="1">Croissant</option>
            </select>
            
            <select name="limit" class="form-control" aria-label="Nombre de résultats affichés par page">
                <option value="20">20 / page</option>
                <option value="40">40 / page</option>
                <option value="60">60 / page</option>
            </select>

            <?php if(!empty($topFilters)): foreach ($topFilters as $filter): ?>
            <select name="attr_<?= (int)($filter['id'] ?? 0) ?>" class="form-control filter-select" aria-label="Filtrer par attribut">
                <option value=""><?= $this->e($filter['title'] ?? 'Attribut') ?> : Tous</option>
                <?php if(!empty($filter['values'])): foreach ($filter['values'] as $val): ?>
                <option value="<?= (int)($val['id'] ?? 0) ?>"><?= $this->e($val['value_text'] ?? '') ?></option>
                <?php endforeach; endif; ?>
            </select>
            <?php endforeach; endif; ?>

