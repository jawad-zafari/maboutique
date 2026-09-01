<?php
$productInfo = $data['productInfo'] ?? [];
$reviewInfo = $data['naghdInfo'] ?? [];
$isEdit = isset($reviewInfo['title']);
$pId = (int)($productInfo['id'] ?? 0);
$rId = (int)($reviewInfo['id'] ?? 0);
?>
<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-pen-nib" aria-hidden="true"></i> 
            <?= $isEdit ? 'Modifier la critique' : 'Ajouter une critique' ?>
            <span class="separator">/</span>
            <span class="sub-breadcrumb-info"><?= $this->e($productInfo['title'] ?? '') ?></span>
        </div>
        <div class="admin-actions">
            <a href="#" class="btn-admin-back js-back-button" aria-label="Retour">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
            </a>
        </div>
    </header>

   
</div>
<script src="<?= URL ?>public/assets/js/admin_product.js" defer></script>