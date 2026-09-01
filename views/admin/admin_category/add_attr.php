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

    <div class="admin-form-box">
        <form action="<?= URL ?>AdminCategory/addAttribute/<?= (int)($data['categoryInfo']['id'] ?? 0) ?>/<?= (int)($data['parentId'] ?? 0) ?>/<?= (int)($data['edit'] ?? 0) ?>" method="post" id="formAttributeManage">
            
            <input type="hidden" name="csrf_token" value="<?= $this->e($data['csrf_token'] ?? '') ?>">

            <div class="form-group">
                <label for="attributeTitle">Titre de l'attribut * :</label>
                <input id="attributeTitle" type="text" name="title" class="form-control" 
                       value="<?= $isEdit ? $this->e($editInfo['title'] ?? '') : '' ?>" required aria-required="true">
            </div>

            <div class="form-group">
                <label for="attributeParent">Attribut parent :</label>
                <select id="attributeParent" name="parent" class="form-control" aria-label="Sélectionner l'attribut parent">
                    <option value="0">-- Sélectionner (Attribut Principal) --</option>
                    <?php
                    $selectedId = (int)($data['parentId'] ?? 0);
                    if(!empty($data['attr'])):
                        foreach ($data['attr'] as $row):
                            if ($isEdit && $row['id'] == $editInfo['id']) continue;
                            $isSelected = ($row['id'] == $selectedId) ? 'selected' : '';
                    ?>
                        <option value="<?= (int)$row['id'] ?>" <?= $isSelected ?>>
                            <?= $this->e($row['title']) ?>
                        </option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <button type="submit" class="btn-admin-submit" aria-label="Sauvegarder l'attribut">
                <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Enregistrer l'attribut
            </button>
            
        </form>
    </div>
</div>