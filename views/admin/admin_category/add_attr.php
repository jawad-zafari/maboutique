<?php
$editInfo = $data['editInfo'] ?? [];
$isEdit = !empty($editInfo['title']);
?>
<div class="admin-container">
    
    <header class="admin-header">
        <div class="admin-breadcrumb" aria-label="Fil d'Ariane">
            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> 
            <?= !$isEdit ? 'Créer un nouvel attribut' : 'Modifier l\'attribut' ?>
            <span class="separator">/</span>
            <span class="sub-breadcrumb-info">
                Catégorie : <?= $this->e($data['categoryInfo']['title'] ?? '') ?> 
                <?= !empty($data['attrInfo']['id']) ? '| Parent : ' . $this->e($data['attrInfo']['title'] ?? '') : '' ?>
            </span>
        </div>
        <div class="admin-actions">
            <a href="<?= URL ?>AdminCategory/showAttributes/<?= (int)($data['categoryInfo']['id'] ?? 0) ?>/<?= (int)($data['parentId'] ?? 0) ?>" class="btn-admin-back" aria-label="Retour à la liste des attributs">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Retour
            </a>
        </div>
    </header>

   