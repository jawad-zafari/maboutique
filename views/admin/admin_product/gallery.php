<?php
$gallery = $data['gallery'] ?? [];
$productInfo = $data['productInfo'] ?? [];
$pId = (int)($productInfo['id'] ?? 0);
?>
<div class="admin-container">
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert-sticky success" role="alert">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i> 
            <span>L'action a été effectuée avec succès !</span>
        </div>
    <?php endif; ?>

    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-images" aria-hidden="true"></i> Galerie du produit
            <span class="separator">/</span>
            <span class="active-breadcrumb-item"><?= $this->e($productInfo['title'] ?? '') ?></span>
        </div>
        <div class="admin-actions">
            <a href="#" class="btn-admin-back js-back-button" aria-label="Retour">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
            </a>
        </div>
    </header>

    <div class="admin-form-box form-box-wide mx-auto mb-25">
        <form action="<?= URL ?>AdminProduct/addGallery/<?= $pId ?>" method="post" enctype="multipart/form-data" id="formAddGallery">
            
            <input type="hidden" name="csrf_token" value="<?= $this->e($data['csrf_token'] ?? '') ?>">
            
            <div class="form-group">
                <label for="galleryImages">Ajouter de nouvelles images (Sélection multiple possible) :</label>
                <input type="file" id="galleryImages" name="images[]" class="form-control" multiple accept="image/jpeg, image/png, image/webp" required>
            </div>
            
            <div class="flex-end-container mt-15">
                <button type="submit" class="btn-admin-submit" aria-label="Uploader les images">
                    <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i> Uploader les images
                </button>
            </div>
        </form>
    </div>

    