<?php 
$editSlider = $data['editSlider'] ?? null; 
$isEditMode = ($editSlider !== null);
$sliders = $data['slider'] ?? [];
$sId = (int)($editSlider['id'] ?? 0);
?>
<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-images" aria-hidden="true"></i> 
            <?= $isEditMode ? "Modifier le Slide : " . $this->e($editSlider['title'] ?? '') : "Gestion du Diaporama" ?>
        </div>
        <?php if ($isEditMode): ?>
            <div class="admin-actions">
                <a href="<?= URL ?>AdminSlider/index" class="btn-admin-back" aria-label="Annuler l'édition et retourner">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
                </a>
            </div>
        <?php endif; ?>
    </header>

   