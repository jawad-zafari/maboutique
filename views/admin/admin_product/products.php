<?php $products = $data['product'] ?? []; ?>
<div class="admin-container">
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert-sticky success" role="alert" aria-live="polite">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i> 
            <span>L'action a été effectuée avec succès !</span>
        </div>
    <?php endif; ?>

    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-box-open" aria-hidden="true"></i> Gestion des Produits
        </div>
        <div class="admin-actions">
            <a href="<?= URL ?>AdminProduct/addProduct" class="btn-admin-primary" aria-label="Ajouter un nouveau produit">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Ajouter un produit
            </a>
            <button type="submit" form="formProductsSelection" id="btnDeleteProducts" class="btn-admin-danger" aria-label="Supprimer les produits sélectionnés">
                <i class="fa-solid fa-trash" aria-hidden="true"></i> Supprimer
            </button>
        </div>
    </header>

   