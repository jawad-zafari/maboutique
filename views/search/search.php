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
        
        