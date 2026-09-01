<?php 
$productInfo = $data['productInfo'] ?? [];
$isEdit = isset($productInfo['title']);
$pId = (int)($productInfo['id'] ?? 0);
?>
<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-box" aria-hidden="true"></i> <?= $isEdit ? 'Modifier le produit' : 'Créer un nouveau produit' ?>
        </div>
        <div class="admin-actions">
            <a href="<?= URL ?>AdminProduct/index" class="btn-admin-back" aria-label="Retourner à la liste des produits">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
            </a>
        </div>
    </header>

    <form action="<?= URL ?>AdminProduct/addProduct/<?= $pId; ?>" method="post" enctype="multipart/form-data" id="formAddProduct">
        
        <!-- Jeton CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $this->e($data['csrf_token'] ?? '') ?>">

        
    </form>
</div>