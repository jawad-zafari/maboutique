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
                
                <div class="form-group">
                    <label for="productPrice">Prix (en €) * :</label>
                    <input type="number" id="productPrice" name="price" class="form-control" value="<?= (int)($productInfo['price'] ?? 0) ?>" min="0" required aria-required="true">
                </div>

                <div class="form-group">
                    <label for="productDiscount">Réduction (%) :</label>
                    <input type="number" id="productDiscount" name="discount" class="form-control" value="<?= (int)($productInfo['discount_percent'] ?? 0) ?>" min="0" max="100">
                </div>
            </div>

            <div class="admin-divider"></div>

            <div class="form-group">
                <label for="productImage">Image Principale (Format JPG/PNG/WEBP) :</label>
                <?php if ($isEdit): ?>
                    <div class="current-image-box mb-10">
                        <span class="image-label text-muted">Image actuelle :</span>
                        <img src="<?= URL ?>public/images/products/<?= $pId ?>/product_220.jpg?v=<?= time() ?>" alt="Aperçu" class="preview-thumb-small" onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>
                <input type="file" id="productImage" name="image" class="form-control" accept="image/jpeg, image/png, image/webp" <?= !$isEdit ? 'required' : '' ?>>
            </div>

            <div class="form-group mt-20">
                <label for="editorDescription">Description détaillée du produit :</label>
                <textarea id="editorDescription" name="description" class="form-control textarea-tall" aria-label="Description"><?= $this->e($productInfo['description'] ?? '') ?></textarea>
            </div>

            <div class="admin-divider"></div>

            <div class="form-row-half">
                <div class="form-group">
                    <label for="colorSelect">Ajouter des Couleurs :</label>
                    <select id="colorSelect" class="form-control">
                        <option value="0">-- Sélectionner une couleur --</option>
                        <?php foreach ($data['color'] ?? [] as $row): ?>
                            <!-- SÉCURITÉ : data-title échappé pour protéger le JS (DOM-based XSS) -->
                            <option value="<?= (int)$row['id']; ?>" data-title="<?= $this->e($row['title']) ?>">
                                <?= $this->e($row['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="colorsContainer" class="tags-container" aria-live="polite">
                        <?php foreach ($productInfo['colorsInfo'] ?? [] as $row): ?>
                            <span class="tag-item">
                                <?= $this->e($row['title']) ?>
                                <input type="hidden" name="color[]" value="<?= (int)$row['id']; ?>">
                                <i class="fa-solid fa-circle-xmark btn-remove-tag" aria-hidden="true" title="Retirer cette couleur"></i>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="garanteeSelect">Ajouter des Garanties :</label>
                    <select id="garanteeSelect" class="form-control">
                        <option value="0">-- Sélectionner une garantie --</option>
                        <?php foreach ($data['garantee'] ?? [] as $row): ?>
                            <!-- SÉCURITÉ : data-title échappé pour protéger le JS (DOM-based XSS) -->
                            <option value="<?= (int)$row['id']; ?>" data-title="<?= $this->e($row['title']) ?>">
                                <?= $this->e($row['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="garanteesContainer" class="tags-container" aria-live="polite">
                        <?php foreach ($productInfo['garanteesInfo'] ?? [] as $row): ?>
                            <span class="tag-item">
                                <?= $this->e($row['title']) ?>
                                <input type="hidden" name="garantee[]" value="<?= (int)$row['id']; ?>">
                                <i class="fa-solid fa-circle-xmark btn-remove-tag" aria-hidden="true" title="Retirer cette garantie"></i>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-action-right mt-30 flex-end-container">
                <button type="submit" class="btn-admin-submit-new" aria-label="Enregistrer les informations du produit">
                    <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Enregistrer le produit
                </button>
            </div>

        </div>
    </form>
</div>