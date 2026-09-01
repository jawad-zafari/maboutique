<?php
$reviews = $data['naghd'] ?? [];
$productInfo = $data['productInfo'] ?? [];
$pId = (int)($productInfo['id'] ?? 0);
?>
<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-comments" aria-hidden="true"></i> Gestion des Critiques
            <span class="separator">/</span>
            <span class="active-breadcrumb-item"><?= $this->e($productInfo['title'] ?? '') ?></span>
        </div>
        <div class="admin-actions">
            <a href="<?= URL ?>AdminProduct/addReview/<?= $pId ?>" class="btn-admin-primary" aria-label="Ajouter une critique">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter une critique
            </a>
            <button type="button" class="btn-admin-danger" id="btnDeleteReview" aria-label="Supprimer les critiques cochées">
                <i class="fa-solid fa-trash" aria-hidden="true"></i> Supprimer
            </a>
            <a href="#" class="btn-admin-back js-back-button" aria-label="Retour">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
            </a>
        </div>
    </header>

   