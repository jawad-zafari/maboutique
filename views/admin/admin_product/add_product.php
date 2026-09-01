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

        <div class="admin-form-box form-box-wide mx-auto">
            
            <div class="form-group">
                <label for="productTitle">Titre du produit * :</label>
                <!-- Injection des données sécurisées via la méthode e() -->
                <input type="text" id="productTitle" name="title" class="form-control" value="<?= $isEdit ? $this->e($productInfo['title'] ?? '') : '' ?>" required aria-required="true">
            </div>

            <div class="form-row-triple">
                <div class="form-group">
                    <label for="productCategory">Catégorie * :</label>
                    <select id="productCategory" name="categoryId" class="form-control" required aria-required="true">
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($data['category'] ?? [] as $row): 
                            $selected = ($isEdit && $productInfo['category_id'] == $row['id']) ? 'selected' : '';
                        ?>
                            <option value="<?= (int)$row['id']; ?>" <?= $selected ?>><?= $this->e($row['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                
        </div>
    </form>
</div>