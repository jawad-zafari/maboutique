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

    <?php if (isset($_GET['success'])): ?>
        <div class="alert-sticky success" role="alert" aria-live="polite">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i> 
            <span>
                <?php
                if($_GET['success'] === 'add') echo "Le nouveau slide a été ajouté avec succès !";
                elseif($_GET['success'] === 'update') echo "Le slide a été mis à jour avec succès !";
                elseif($_GET['success'] === 'delete') echo "La suppression a été effectuée avec succès !";
                ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert-sticky danger" role="alert" aria-live="polite">
            <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> 
            <span>Erreur lors du téléchargement de l'image. Vérifiez le format (JPG/PNG/WEBP) et la taille (Max 5Mo).</span>
        </div>
    <?php endif; ?>

    