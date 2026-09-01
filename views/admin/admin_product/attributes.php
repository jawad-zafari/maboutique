<?php
$attr = $data['attr'] ?? [];
$productInfo = $data['productInfo'] ?? [];
$pId = (int)($productInfo['id'] ?? 0);
?>
<div class="admin-container">
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert-sticky success" role="alert">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i> 
            <span>Les attributs du produit ont été mis à jour avec succès !</span>
        </div>
    <?php endif; ?>

    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-list-check" aria-hidden="true"></i> Attributs du produit
            <span class="separator">/</span>
            <span class="active-breadcrumb-item"><?= $this->e($productInfo['title'] ?? '') ?></span>
        </div>
        <div class="admin-actions">
            <a href="#" class="btn-admin-back js-back-button" aria-label="Retour">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
            </a>
        </div>
    </header>

   