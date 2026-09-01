<?php
$categoryInfo = $data['categoryInfo'] ?? [];
$isEdit = !empty($data['edit']) && $data['edit'] > 0;
?>
<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> 
            <?= !$isEdit ? 'Créer une nouvelle catégorie' : 'Modifier la catégorie' ?>
        </div>
        <div class="admin-actions">
            <a href="<?= URL ?>AdminCategory/showChildren/<?= (int)($data['parentId'] ?? 0) ?>" class="btn-admin-back" aria-label="Retourner à la liste">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
            </a>
        </div>
    </header>

    